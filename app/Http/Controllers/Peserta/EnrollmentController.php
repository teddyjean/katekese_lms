<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('profile');

        $enrolledIds = $user->batchesAsPeserta()->pluck('batches.id');

        $available = Batch::with('program')
            ->where('status', 'active')
            ->whereNotIn('id', $enrolledIds)
            ->orderByDesc('id')
            ->get();

        $enrollments = $user->batchesAsPeserta()->with('program')->get();

        return view('peserta.kelas.index', compact('available', 'enrollments', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate(['batch_id' => 'required|exists:batches,id']);

        $user = auth()->user()->load('profile');

        if (! $user->hasCompleteProfile()) {
            return redirect()->route('peserta.profile.edit')
                ->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar kelas.');
        }

        $batch = Batch::findOrFail($request->batch_id);

        if ($user->batchesAsPeserta()->where('batches.id', $batch->id)->exists()) {
            return back()->with('error', 'Anda sudah pernah mendaftar di kelas ini.');
        }

        $user->batchesAsPeserta()->attach($batch->id, [
            'joined_at' => now()->toDateString(),
            'status'    => 'pending',
        ]);

        return back()->with('success', "Pendaftaran ke kelas {$batch->name} berhasil dikirim. Menunggu verifikasi katekis.");
    }
}
