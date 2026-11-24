<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Material;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::orderByDesc('id')->get();
        $query = Test::with('batch')->withCount('questions');
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        $tests = $query->orderByDesc('id')->paginate(20)->withQueryString();
        return view('admin.tests.index', compact('tests', 'batches'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')
            ->whereHas('katekis', fn ($q) => $q->where('users.id', auth()->id()))
            ->orderByDesc('id')->get();
        $materialsByBatch = Material::orderBy('order')->get()->groupBy('batch_id');
        return view('admin.tests.create', compact('batches', 'materialsByBatch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id'         => 'required|exists:batches,id',
            'material_id'      => 'nullable|exists:materials,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:300',
        ]);

        $batch = Batch::findOrFail($request->batch_id);
        Gate::authorize('manage', $batch);
        if ($batch->isLocked()) {
            return back()->withInput()->withErrors(['batch_id' => 'Kelas ini sudah selesai/diarsipkan, tidak bisa membuat test baru.']);
        }

        $test = Test::create([
            'batch_id'         => $request->batch_id,
            'material_id'      => $request->material_id ?: null,
            'created_by'       => auth()->id(),
            'title'            => $request->title,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'is_active'        => false,
        ]);

        return redirect()->route('admin.tests.show', $test)->with('success', 'Test dibuat. Sekarang tambahkan soal.');
    }

    public function show(Test $test)
    {
        $test->load(['questions.options', 'attempts.user', 'batch']);
        $canManage = Gate::allows('manage', $test->batch);
        return view('admin.tests.show', compact('test', 'canManage'));
    }

    public function edit(Test $test)
    {
        Gate::authorize('manage', $test->batch);

        $batches = Batch::where(fn ($q) => $q
                ->where('status', 'active')
                ->whereHas('katekis', fn ($qq) => $qq->where('users.id', auth()->id()))
            )
            ->orWhere('id', $test->batch_id)
            ->orderByDesc('id')->get();
        $materialsByBatch = Material::orderBy('order')->get()->groupBy('batch_id');
        return view('admin.tests.edit', compact('test', 'batches', 'materialsByBatch'));
    }

    public function update(Request $request, Test $test)
    {
        Gate::authorize('manage', $test->batch);

        $request->validate([
            'batch_id'         => 'required|exists:batches,id',
            'material_id'      => 'nullable|exists:materials,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1|max:300',
        ]);

        Gate::authorize('manage', Batch::findOrFail($request->batch_id));

        $test->update($request->only('batch_id', 'title', 'description', 'duration_minutes') + [
            'material_id' => $request->material_id ?: null,
        ]);
        return redirect()->route('admin.tests.show', $test)->with('success', 'Test berhasil diperbarui.');
    }

    public function destroy(Test $test)
    {
        Gate::authorize('manage', $test->batch);

        $batchId = $test->batch_id;
        $test->delete();
        return redirect()->to(route('admin.batches.show', $batchId) . '?tab=test')
            ->with('success', 'Test berhasil dihapus.');
    }

    public function toggleActive(Test $test)
    {
        Gate::authorize('manage', $test->batch);

        $test->update(['is_active' => !$test->is_active]);
        $status = $test->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Test berhasil {$status}.");
    }

    public function storeQuestion(Request $request, Test $test)
    {
        Gate::authorize('manage', $test->batch);

        if ($test->batch->isLocked()) {
            return back()->withErrors(['question_text' => 'Kelas ini sudah selesai/diarsipkan, tidak bisa menambah soal.']);
        }

        $request->validate([
            'question_text' => 'required|string',
            'type'          => 'required|in:multiple_choice,essay',
            'points'        => 'required|integer|min:1',
            'options'       => 'required_if:type,multiple_choice|array|min:2',
            'options.*'     => 'required_if:type,multiple_choice|string',
            'correct'       => 'required_if:type,multiple_choice',
        ]);

        $order = $test->questions()->max('order') ?? 0;

        $question = $test->questions()->create([
            'question_text' => $request->question_text,
            'type'          => $request->type,
            'points'        => $request->points,
            'order'         => $order + 1,
        ]);

        if ($request->type === 'multiple_choice') {
            foreach ($request->options as $i => $optText) {
                $question->options()->create([
                    'option_text' => $optText,
                    'is_correct'  => ($i == $request->correct),
                ]);
            }
        }

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function destroyQuestion(Test $test, Question $question)
    {
        Gate::authorize('manage', $test->batch);

        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}
