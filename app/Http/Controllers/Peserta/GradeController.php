<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Test;

class GradeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');

        $assignments = Assignment::with([
            'batch',
            'submissions' => fn($q) => $q->where('user_id', $user->id),
        ])
            ->whereIn('batch_id', $batchIds)
            ->whereHas('submissions', fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('deadline')
            ->get();

        $tests = Test::with([
            'batch',
            'attempts' => fn($q) => $q->where('user_id', $user->id)->whereNotNull('submitted_at'),
        ])
            ->whereIn('batch_id', $batchIds)
            ->whereHas('attempts', fn($q) => $q->where('user_id', $user->id)->whereNotNull('submitted_at'))
            ->orderByDesc('id')
            ->get();

        return view('peserta.nilai.index', compact('assignments', 'tests'));
    }
}
