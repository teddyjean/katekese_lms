@extends('layouts.app')
@section('title', 'Jadwal Pertemuan')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Jadwal Pertemuan</h1>
    <p class="text-gray-500 text-sm mt-1">Jadwal pertemuan tatap muka kelas Anda</p>
</div>

@forelse($meetings as $meeting)
@php $attendance = $myAttendances->get($meeting->id); @endphp
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3">
    <div class="flex justify-between items-start gap-3">
        <div>
            <p class="font-medium text-gray-800">{{ $meeting->scheduled_at->format('d M Y, H:i') }}</p>
            <p class="text-xs text-blue-600 mt-1">{{ $meeting->batch->name }}</p>
            <p class="text-xs text-gray-400 mt-1">
                {{ $meeting->location ?: 'Tempat belum ditentukan' }}
                @if($meeting->material) &middot; {{ $meeting->material->title }} @endif
            </p>
        </div>
        <div class="ml-4 shrink-0 text-right">
            @if($attendance)
                @php
                    $statusLabel = ['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'][$attendance->status];
                    $statusColor = match($attendance->status) {
                        'hadir' => 'bg-emerald-100 text-emerald-700',
                        'izin', 'sakit' => 'bg-amber-100 text-amber-700',
                        default => 'bg-red-100 text-red-700',
                    };
                @endphp
                <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
            @elseif($meeting->isCheckinOpen())
                <form method="POST" action="{{ route('peserta.presensi.checkin', $meeting) }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Hadir
                    </button>
                </form>
            @elseif($meeting->canKatekisEdit())
                <span class="text-xs text-gray-400">Presensi ditutup, menunggu katekis</span>
            @else
                <span class="text-xs text-gray-400">Dibuka {{ $meeting->scheduled_at->format('d M Y, H:i') }}</span>
            @endif
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-xl p-10 text-center text-gray-400 shadow-sm border border-gray-100">
    Belum ada jadwal pertemuan.
</div>
@endforelse
@endsection
