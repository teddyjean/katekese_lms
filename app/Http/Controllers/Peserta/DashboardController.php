<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Test;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');
        $batches = $user->approvedBatchesAsPeserta()->with('program')->get();

        $pendingAssignments = Assignment::whereIn('batch_id', $batchIds)
            ->whereDoesntHave('submissions', fn($q) => $q->where('user_id', $user->id))
            ->where(fn($q) => $q->whereNull('deadline')->orWhere('deadline', '>=', now()))
            ->count();

        $activeTests = Test::whereIn('batch_id', $batchIds)
            ->where('is_active', true)
            ->whereDoesntHave('attempts', fn($q) => $q->where('user_id', $user->id))
            ->count();

        return view('peserta.dashboard', compact('batches', 'pendingAssignments', 'activeTests'));
    }
}
