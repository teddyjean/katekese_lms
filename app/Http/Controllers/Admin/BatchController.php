<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with('program')->withCount(['katekis', 'peserta']);

        if ($request->boolean('mine')) {
            $query->whereHas('katekis', fn ($q) => $q->where('users.id', auth()->id()));
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        $batches = $query->orderByDesc('start_date')->paginate(15)->withQueryString();

        $yearExpr = DB::getDriverName() === 'sqlite' ? "strftime('%Y', start_date)" : "YEAR(start_date)";
        $yearsQuery = Batch::selectRaw("{$yearExpr} as year")
            ->whereNotNull('start_date')
            ->when($request->filled('program_id'), fn ($q) => $q->where('program_id', $request->program_id))
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('admin.batches.index', compact('batches', 'yearsQuery'));
    }

    public function create()
    {
        $programs = Program::where('status', 'active')->orderBy('name')->get();

        $katekisByProgram = $programs->mapWithKeys(fn ($program) => [
            $program->id => $program->katekis()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['users.id', 'users.name'])
                ->map(fn ($k) => ['id' => $k->id, 'name' => $k->name])
                ->values(),
        ]);

        return view('admin.batches.create', compact('programs', 'katekisByProgram'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id'  => 'required|exists:programs,id',
            'name'        => 'required|string|max:255',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'katekis_ids' => 'nullable|array',
            'katekis_ids.*' => 'exists:users,id',
        ]);

        $batch = Batch::create([
            'program_id'  => $request->program_id,
            'name'        => $request->name,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'description' => $request->description,
            'status'      => 'active',
        ]);

        if ($request->filled('katekis_ids')) {
            $batch->katekis()->sync($request->katekis_ids);
        }

        return redirect()->route('admin.batches.show', $batch)->with('success', 'Angkatan berhasil dibuat.');
    }

    public function show(Batch $batch)
    {
        $batch->load(['program', 'katekis']);
        $peserta = $batch->approvedPeserta()->with('profile')->orderBy('name')->get();

        return view('admin.batches.show', compact('batch', 'peserta'));
    }

    public function updateDocumentFields(Request $request, Batch $batch)
    {
        $request->validate([
            'nama_romo'        => 'nullable|string|max:255',
            'tanggal_sakramen' => 'nullable|date',
        ]);
        $batch->update($request->only('nama_romo', 'tanggal_sakramen'));
        return back()->with('success', 'Data dokumen disimpan.');
    }

    public function updateKelulusan(Request $request, Batch $batch, User $user)
    {
        $lulus = $request->filled('lulus') ? (bool) $request->input('lulus') : null;
        $batch->peserta()->updateExistingPivot($user->id, ['lulus' => $lulus]);
        return back();
    }

    public function edit(Batch $batch)
    {
        $programs = Program::where('status', 'active')->orderBy('name')->get();
        return view('admin.batches.edit', compact('batch', 'programs'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'program_id'       => 'required|exists:programs,id',
            'name'             => 'required|string|max:255',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'description'      => 'nullable|string',
            'status'           => 'required|in:active,completed,archived',
            'nama_romo'        => 'nullable|string|max:255',
            'tanggal_sakramen' => 'nullable|date',
        ]);

        $batch->update($request->only('program_id', 'name', 'start_date', 'end_date', 'description', 'status', 'nama_romo', 'tanggal_sakramen'));

        return redirect()->route('admin.batches.show', $batch)->with('success', 'Angkatan berhasil diperbarui.');
    }

    // ── Katekis ──────────────────────────────────────────────────────────────

    public function assignKatekis(Request $request, Batch $batch)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $batch->katekis()->syncWithoutDetaching([$request->user_id]);
        return back()->with('success', 'Katekis berhasil ditambahkan.');
    }

    public function removeKatekis(Batch $batch, User $user)
    {
        $batch->katekis()->detach($user->id);
        return back()->with('success', 'Katekis berhasil dihapus dari angkatan.');
    }

    // ── Peserta Enrollment ───────────────────────────────────────────────────

    public function assignPeserta(Request $request, Batch $batch)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $batch->peserta()->syncWithoutDetaching([
            $request->user_id => ['joined_at' => now()->toDateString(), 'status' => 'approved'],
        ]);
        return back()->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function removePeserta(Batch $batch, User $user)
    {
        $batch->peserta()->detach($user->id);
        return back()->with('success', 'Peserta berhasil dihapus dari angkatan.');
    }

    public function approvePeserta(Batch $batch, User $user)
    {
        $batch->peserta()->updateExistingPivot($user->id, [
            'status'         => 'approved',
            'rejection_note' => null,
        ]);
        return back()->with('success', "{$user->name} berhasil diterima di kelas {$batch->name}.");
    }

    public function rejectPeserta(Request $request, Batch $batch, User $user)
    {
        $request->validate(['rejection_note' => 'nullable|string|max:500']);

        $batch->peserta()->updateExistingPivot($user->id, [
            'status'         => 'rejected',
            'rejection_note' => $request->rejection_note,
        ]);
        return back()->with('success', "Pendaftaran {$user->name} telah ditolak.");
    }

    public function transferPeserta(Request $request, Batch $batch, User $user)
    {
        $request->validate(['target_batch_id' => 'required|exists:batches,id|different:batch_id']);

        $targetBatchId = $request->target_batch_id;

        // Remove from current batch, add to target as approved
        $batch->peserta()->detach($user->id);

        $targetBatch = Batch::findOrFail($targetBatchId);
        $targetBatch->peserta()->syncWithoutDetaching([
            $user->id => ['joined_at' => now()->toDateString(), 'status' => 'approved'],
        ]);

        return back()->with('success', "{$user->name} berhasil dimutasi ke kelas {$targetBatch->name}.");
    }
}
