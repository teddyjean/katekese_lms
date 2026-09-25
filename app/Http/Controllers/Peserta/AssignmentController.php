<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
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
            ->orderByDesc('deadline')
            ->paginate(20);

        return view('peserta.tugas.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');
        abort_unless($batchIds->contains($assignment->batch_id), 403);

        $submission = $assignment->submissionByUser($user->id);
        return view('peserta.tugas.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');
        abort_unless($batchIds->contains($assignment->batch_id), 403);

        if ($assignment->submissionByUser($user->id)) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini.');
        }

        $request->validate([
            'file'  => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'notes' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $path = $file->store('submissions', 'public');

        AssignmentSubmission::create([
            'assignment_id'      => $assignment->id,
            'user_id'            => $user->id,
            'file_path'          => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'notes'              => $request->notes,
            'submitted_at'       => now(),
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
