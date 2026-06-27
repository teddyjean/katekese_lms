@extends('layouts.app')
@section('title', 'Kelola Presensi')
@section('content')

<div class="mb-6">
    <a href="{{ route('admin.batches.show', $meeting->batch) }}?tab=presensi" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Kelas</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Presensi: {{ $meeting->scheduled_at->format('d M Y, H:i') }}</h1>
    <p class="text-gray-500 text-sm mt-1">
        {{ $meeting->batch->name }}
        @if($meeting->location) &middot; {{ $meeting->location }} @endif
        @if($meeting->material) &middot; Materi: {{ $meeting->material->title }} @endif
    </p>
</div>

@if($students->isEmpty())
<div class="bg-white rounded-2xl p-10 text-center shadow-sm border border-gray-100 text-gray-400">
    Belum ada peserta yang disetujui di kelas ini.
</div>
@else
<form method="POST" action="{{ route('admin.meetings.attendance.update', $meeting) }}">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[680px] text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Siswa</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Sumber</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($students as $student)
                @php $attendance = $attendances->get($student->id); @endphp
                <tr class="hover:bg-gray-50/80 transition-colors align-top">
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-gray-800">{{ $student->name }}</p>
                        <p class="text-xs text-gray-400">{{ $student->email }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        @if(!$attendance)
                            <span class="text-xs text-gray-400">-</span>
                        @elseif($attendance->isSelfReported())
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Self check-in</span>
                        @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Dikonfirmasi katekis</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <select name="attendances[{{ $student->id }}][status]"
                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-- Pilih --</option>
                            @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $value => $label)
                                <option value="{{ $value }}" @selected(old("attendances.$student->id.status", $attendance?->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-5 py-3.5 min-w-[220px]">
                        <input type="text" name="attendances[{{ $student->id }}][notes]"
                               value="{{ old("attendances.$student->id.notes", $attendance?->notes) }}"
                               placeholder="Catatan (opsional)"
                               class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
                Simpan Presensi
            </button>
        </div>
    </div>
</form>
@endif

@endsection
