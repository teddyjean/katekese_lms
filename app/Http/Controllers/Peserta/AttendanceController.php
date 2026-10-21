<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Meeting;

class AttendanceController extends Controller
{
    public function index()
    {
        $batchIds = auth()->user()->approvedBatchesAsPeserta()->pluck('batches.id');

        $meetings = Meeting::whereIn('batch_id', $batchIds)
            ->with(['batch', 'material'])
            ->orderBy('scheduled_at')
            ->get();

        $myAttendances = Attendance::where('user_id', auth()->id())
            ->whereIn('meeting_id', $meetings->pluck('id'))
            ->get()->keyBy('meeting_id');

        return view('peserta.presensi.index', compact('meetings', 'myAttendances'));
    }

    public function checkin(Meeting $meeting)
    {
        abort_unless(
            auth()->user()->approvedBatchesAsPeserta->pluck('id')->contains($meeting->batch_id),
            403
        );
        abort_unless($meeting->isCheckinOpen(), 422, 'Presensi belum dibuka atau sudah ditutup.');

        Attendance::firstOrCreate(
            ['meeting_id' => $meeting->id, 'user_id' => auth()->id()],
            ['status' => 'hadir', 'recorded_by' => null]
        );

        return back()->with('success', 'Presensi berhasil dicatat. Terima kasih!');
    }
}
