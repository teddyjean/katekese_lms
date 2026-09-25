<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');

        $materials = Material::with('batch')
            ->whereIn('batch_id', $batchIds)
            ->orderBy('batch_id')
            ->orderBy('order')
            ->paginate(20);

        return view('peserta.materi.index', compact('materials'));
    }

    public function show(Material $material)
    {
        $batchIds = auth()->user()->approvedBatchesAsPeserta()->pluck('batches.id');
        abort_unless($batchIds->contains($material->batch_id), 403);
        return view('peserta.materi.show', compact('material'));
    }
}
