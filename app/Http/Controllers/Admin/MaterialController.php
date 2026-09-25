<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::orderByDesc('id')->get();
        $query = Material::with(['batch', 'uploader'])->orderBy('batch_id')->orderBy('order');
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }
        $materials = $query->paginate(20)->withQueryString();
        return view('admin.materials.index', compact('materials', 'batches'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')
            ->whereHas('katekis', fn ($q) => $q->where('users.id', auth()->id()))
            ->orderByDesc('id')->get();
        return view('admin.materials.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|required_without:video_url|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            'video_url'   => 'nullable|required_without:file|url|max:2048',
        ]);

        $batch = Batch::findOrFail($request->batch_id);
        Gate::authorize('manage', $batch);
        if ($batch->isLocked()) {
            return back()->withInput()->withErrors(['batch_id' => 'Kelas ini sudah selesai/diarsipkan, tidak bisa upload materi baru.']);
        }

        $path = null;
        $originalName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('materials', 'b2');
            $originalName = $file->getClientOriginalName();
        }

        $lastOrder = Material::where('batch_id', $request->batch_id)->max('order') ?? 0;

        Material::create([
            'batch_id'           => $request->batch_id,
            'uploaded_by'        => auth()->id(),
            'title'              => $request->title,
            'description'        => $request->description,
            'file_path'          => $path,
            'file_original_name' => $originalName,
            'file_disk'          => $path ? 'b2' : 'public',
            'video_url'          => $request->video_url,
            'order'              => $lastOrder + 1,
        ]);

        return redirect()->to(route('admin.batches.show', $request->batch_id) . '?tab=materi')
            ->with('success', 'Materi berhasil diupload.');
    }

    public function show(Material $material)
    {
        $material->load(['batch', 'assignments', 'tests']);
        $canManage = Gate::allows('manage', $material->batch);
        return view('admin.materials.show', compact('material', 'canManage'));
    }

    public function edit(Material $material)
    {
        Gate::authorize('manage', $material->batch);

        $batches = Batch::where(fn ($q) => $q
                ->where('status', 'active')
                ->whereHas('katekis', fn ($qq) => $qq->where('users.id', auth()->id()))
            )
            ->orWhere('id', $material->batch_id)
            ->orderByDesc('id')->get();
        return view('admin.materials.edit', compact('material', 'batches'));
    }

    public function update(Request $request, Material $material)
    {
        Gate::authorize('manage', $material->batch);

        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            'video_url'   => 'nullable|url|max:2048',
        ]);

        Gate::authorize('manage', Batch::findOrFail($request->batch_id));

        $data = [
            'batch_id'    => $request->batch_id,
            'title'       => $request->title,
            'description' => $request->description,
            'video_url'   => $request->video_url,
        ];

        if ($request->hasFile('file')) {
            if ($material->file_path) {
                Storage::disk($material->file_disk)->delete($material->file_path);
            }
            $file = $request->file('file');
            $data['file_path'] = $file->store('materials', 'b2');
            $data['file_original_name'] = $file->getClientOriginalName();
            $data['file_disk'] = 'b2';
        }

        $material->update($data);
        return redirect()->to(route('admin.batches.show', $material->batch_id) . '?tab=materi')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        Gate::authorize('manage', $material->batch);

        if ($material->file_path) {
            Storage::disk($material->file_disk)->delete($material->file_path);
        }
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }
}
