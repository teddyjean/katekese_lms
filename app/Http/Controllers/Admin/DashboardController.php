<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function yearExpr(string $column): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "strftime('%Y', {$column})"
            : "YEAR({$column})";
    }

    public function index()
    {
        $stats = [
            'total_peserta' => User::where('role', 'peserta')->count(),
            'total_katekis' => User::where('role', 'katekis')->count(),
            'total_program' => Program::count(),
            'total_angkatan' => Batch::where('status', 'active')->count(),
        ];

        $yearExpr = $this->yearExpr('batches.start_date');

        $pesertaPerProgramTahun = $this->groupedChartData(
            DB::table('batch_participants')
                ->join('batches', 'batches.id', '=', 'batch_participants.batch_id')
                ->join('programs', 'programs.id', '=', 'batches.program_id')
                ->where('batch_participants.status', 'approved')
                ->whereNotNull('batches.start_date')
                ->selectRaw("programs.name as program, {$yearExpr} as tahun, COUNT(DISTINCT batch_participants.user_id) as total")
                ->groupBy('programs.name', 'tahun')
                ->orderBy('tahun')
                ->get()
        );

        $lulusPerProgramTahun = $this->groupedChartData(
            DB::table('batch_participants')
                ->join('batches', 'batches.id', '=', 'batch_participants.batch_id')
                ->join('programs', 'programs.id', '=', 'batches.program_id')
                ->where('batch_participants.status', 'approved')
                ->where('batches.status', 'completed')
                ->whereNotNull('batches.start_date')
                ->selectRaw("programs.name as program, {$yearExpr} as tahun, COUNT(DISTINCT batch_participants.user_id) as total")
                ->groupBy('programs.name', 'tahun')
                ->orderBy('tahun')
                ->get()
        );

        $katekisPerBidang = Program::withCount('katekis')
            ->orderBy('name')
            ->get()
            ->map(fn ($program) => ['label' => $program->name, 'total' => $program->katekis_count]);

        return view('admin.dashboard', compact(
            'stats', 'pesertaPerProgramTahun', 'lulusPerProgramTahun', 'katekisPerBidang'
        ));
    }

    private function groupedChartData($rows): array
    {
        $years = $rows->pluck('tahun')->unique()->sort()->values();
        $programs = $rows->pluck('program')->unique()->sort()->values();

        $datasets = $programs->map(function ($program) use ($rows, $years) {
            return [
                'label' => $program,
                'data' => $years->map(function ($year) use ($rows, $program) {
                    return (int) ($rows->first(fn ($r) => $r->program === $program && $r->tahun === $year)?->total ?? 0);
                })->values(),
            ];
        })->values();

        return [
            'labels' => $years->values(),
            'datasets' => $datasets,
        ];
    }
}
