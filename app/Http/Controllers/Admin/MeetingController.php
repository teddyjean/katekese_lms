<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Material;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function store(Request $request, Batch $batch)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'jam'         => 'required|date_format:H:i',
            'location'    => 'nullable|string|max:255',
            'material_id' => 'nullable|exists:materials,id',
        ]);

        $scheduledAt = Carbon::parse("{$request->tanggal} {$request->jam}");
        $material = $request->material_id ? Material::find($request->material_id) : null;

        $batch->meetings()->create([
            'material_id'  => $material?->id,
            'title'        => $material?->title ?? 'Pertemuan ' . $scheduledAt->format('d M Y'),
            'scheduled_at' => $scheduledAt,
            'location'     => $request->location,
        ]);

        return back()->with('success', 'Jadwal pertemuan berhasil dibuat.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return back()->with('success', 'Jadwal pertemuan berhasil dihapus.');
    }

    public function editAttendance(Meeting $meeting)
    {
        abort_unless($meeting->canKatekisEdit(), 403, 'Presensi belum bisa diedit, tunggu jendela self check-in siswa tutup.');

        $meeting->load('batch');
        $students = $meeting->batch->approvedPeserta;
        $attendances = $meeting->attendances->keyBy('user_id');

        return view('admin.meetings.attendance', compact('meeting', 'students', 'attendances'));
    }

    public function updateAttendance(Request $request, Meeting $meeting)
    {
        abort_unless($meeting->canKatekisEdit(), 403, 'Presensi belum bisa diedit, tunggu jendela self check-in siswa tutup.');

        $request->validate([
            'attendances'            => 'required|array',
            'attendances.*.status'   => 'nullable|in:hadir,izin,sakit,alpha',
            'attendances.*.notes'    => 'nullable|string|max:500',
        ]);

        foreach ($request->input('attendances') as $userId => $data) {
            if (empty($data['status'])) {
                continue; // belum diputuskan katekis, biarkan kosong
            }

            Attendance::updateOrCreate(
                ['meeting_id' => $meeting->id, 'user_id' => $userId],
                [
                    'status'      => $data['status'],
                    'notes'       => $data['notes'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return back()->with('success', 'Presensi berhasil disimpan.');
    }
}
