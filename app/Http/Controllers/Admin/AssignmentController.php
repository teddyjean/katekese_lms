<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Batch;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::orderByDesc('id')->get();
        $query = Assignment::with('batch')->orderByDesc('deadline');
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        $assignments = $query->paginate(20)->withQueryString();
        return view('admin.assignments.index', compact('assignments', 'batches'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')
            ->whereHas('katekis', fn ($q) => $q->where('users.id', auth()->id()))
            ->orderByDesc('id')->get();
        $materialsByBatch = Material::orderBy('order')->get()->groupBy('batch_id');
        return view('admin.assignments.create', compact('batches', 'materialsByBatch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'material_id' => 'nullable|exists:materials,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
            'max_score'   => 'required|integer|min:1|max:1000',
        ]);

        $batch = Batch::findOrFail($request->batch_id);
        Gate::authorize('manage', $batch);
        if ($batch->isLocked()) {
            return back()->withInput()->withErrors(['batch_id' => 'Kelas ini sudah selesai/diarsipkan, tidak bisa membuat tugas baru.']);
        }

        Assignment::create([
            'batch_id'    => $request->batch_id,
            'material_id' => $request->material_id ?: null,
            'created_by'  => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'max_score'   => $request->max_score,
        ]);

        return redirect()->to(route('admin.batches.show', $request->batch_id) . '?tab=tugas')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['batch', 'submissions.user']);
        $peserta = $assignment->batch->peserta;
        $canManage = Gate::allows('manage', $assignment->batch);
        return view('admin.assignments.show', compact('assignment', 'peserta', 'canManage'));
    }

    public function edit(Assignment $assignment)
    {
        Gate::authorize('manage', $assignment->batch);

        $batches = Batch::where(fn ($q) => $q
                ->where('status', 'active')
                ->whereHas('katekis', fn ($qq) => $qq->where('users.id', auth()->id()))
            )
            ->orWhere('id', $assignment->batch_id)
            ->orderByDesc('id')->get();
        $materialsByBatch = Material::orderBy('order')->get()->groupBy('batch_id');
        return view('admin.assignments.edit', compact('assignment', 'batches', 'materialsByBatch'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        Gate::authorize('manage', $assignment->batch);

        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'material_id' => 'nullable|exists:materials,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
            'max_score'   => 'required|integer|min:1|max:1000',
        ]);

        Gate::authorize('manage', Batch::findOrFail($request->batch_id));

        $assignment->update($request->only('batch_id', 'title', 'description', 'deadline', 'max_score') + [
            'material_id' => $request->material_id ?: null,
        ]);
        return redirect()->to(route('admin.batches.show', $assignment->batch_id) . '?tab=tugas')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Assignment $assignment)
    {
        Gate::authorize('manage', $assignment->batch);

        foreach ($assignment->submissions as $sub) {
            Storage::disk('public')->delete($sub->file_path);
        }
        $assignment->delete();
        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        Gate::authorize('manage', $submission->assignment->batch);

        $request->validate([
            'grade'    => 'required|numeric|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'grade'     => $request->grade,
            'feedback'  => $request->feedback,
            'graded_by' => auth()->id(),
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
