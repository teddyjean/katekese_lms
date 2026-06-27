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

        $eligibleOrder = $user->enrollmentEligibleProgramOrder();

        $available = Batch::with('program')
            ->where('status', 'active')
            ->whereNotIn('id', $enrolledIds)
            ->whereHas('program', fn ($q) => $q->where('order', $eligibleOrder))
            ->orderByDesc('id')
            ->get();

        $enrollments = $user->batchesAsPeserta()->with('program')->get();

        $hasActiveEnrollment = $user->batchesAsPeserta()
            ->where('batches.status', 'active')
            ->wherePivotIn('status', ['pending', 'approved'])
            ->exists();

        return view('peserta.kelas.index', compact('available', 'enrollments', 'user', 'eligibleOrder', 'hasActiveEnrollment'));
    }

    public function store(Request $request)
    {
        $request->validate(['batch_id' => 'required|exists:batches,id']);

        $user = auth()->user()->load('profile');

        if (! $user->hasCompleteProfile()) {
            return redirect()->route('peserta.profile.edit')
                ->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar kelas.');
        }

        $batch = Batch::with('program')->findOrFail($request->batch_id);

        $eligibleOrder = $user->enrollmentEligibleProgramOrder();

        if ($batch->program->order !== $eligibleOrder) {
            return back()->with('error', 'Anda tidak memenuhi syarat untuk mendaftar program ini. Periksa kelengkapan data sakramen di profil Anda.');
        }

        $hasActiveEnrollment = $user->batchesAsPeserta()
            ->where('batches.status', 'active')
            ->wherePivotIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActiveEnrollment) {
            return back()->with('error', 'Anda sudah memiliki pendaftaran aktif. Selesaikan kelas yang sedang berjalan terlebih dahulu.');
        }

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
