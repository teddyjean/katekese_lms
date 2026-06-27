<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Material;
use Illuminate\Http\Request;
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
        $batches = Batch::orderByDesc('id')->get();
        return view('admin.materials.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4',
        ]);

        $file = $request->file('file');
        $path = $file->store('materials', 'public');
        $lastOrder = Material::where('batch_id', $request->batch_id)->max('order') ?? 0;

        Material::create([
            'batch_id'           => $request->batch_id,
            'uploaded_by'        => auth()->id(),
            'title'              => $request->title,
            'description'        => $request->description,
            'file_path'          => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'order'              => $lastOrder + 1,
        ]);

        return redirect()->to(route('admin.batches.show', $request->batch_id) . '?tab=materi')
            ->with('success', 'Materi berhasil diupload.');
    }

    public function show(Material $material)
    {
        $material->load(['batch', 'assignments', 'tests']);
        return view('admin.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $batches = Batch::orderByDesc('id')->get();
        return view('admin.materials.edit', compact('material', 'batches'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'batch_id'    => 'required|exists:batches,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4',
        ]);

        $data = [
            'batch_id'    => $request->batch_id,
            'title'       => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($material->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('materials', 'public');
            $data['file_original_name'] = $file->getClientOriginalName();
        }

        $material->update($data);
        return redirect()->to(route('admin.batches.show', $material->batch_id) . '?tab=materi')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        Storage::disk('public')->delete($material->file_path);
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }
}
