@extends('layouts.app')
@section('title', 'Kelas')
@section('content')

<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelas</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar dan kelola kelas yang Anda ikuti</p>
    </div>
    @if(!$user->hasCompleteProfile())
    <a href="{{ route('peserta.profile.edit') }}"
       class="shrink-0 flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
        </svg>
        Lengkapi Profil Dulu
    </a>
    @endif
</div>

{{-- Profile required warning --}}
@if(!$user->hasCompleteProfile())
<div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 mb-6 text-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
    </svg>
    <div>
        <p class="font-semibold">Profil belum dilengkapi</p>
        <p class="mt-0.5 text-amber-700">
            Anda harus <a href="{{ route('peserta.profile.edit') }}" class="underline font-medium">mengisi profil</a> sebelum dapat mendaftar ke kelas manapun.
        </p>
    </div>
</div>
@endif

{{-- Kelas Saya --}}
<h2 class="font-semibold text-gray-700 mb-3">Kelas Saya ({{ $enrollments->count() }})</h2>

@forelse($enrollments as $batch)
    @php
        $status = $batch->pivot->status;
        $statusConfig = match($status) {
            'approved' => ['label' => 'Terdaftar',          'class' => 'bg-emerald-100 text-emerald-700'],
            'pending'  => ['label' => 'Menunggu Verifikasi','class' => 'bg-amber-100 text-amber-700'],
            'rejected' => ['label' => 'Ditolak',            'class' => 'bg-red-100 text-red-700'],
            default    => ['label' => $status,              'class' => 'bg-gray-100 text-gray-500'],
        };
    @endphp
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-3
                {{ $status === 'rejected' ? 'opacity-75' : '' }}">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-semibold text-gray-800">{{ $batch->name }}</p>
                <p class="text-sm text-gray-500">{{ $batch->program->name }}</p>
                @if($status === 'rejected' && $batch->pivot->rejection_note)
                    <p class="text-xs text-red-500 mt-1.5">
                        Alasan penolakan: {{ $batch->pivot->rejection_note }}
                    </p>
                @endif
                @if($status === 'pending')
                    <p class="text-xs text-amber-600 mt-1.5">
                        Pendaftaran Anda sedang ditinjau oleh katekis.
                    </p>
                @endif
            </div>
            <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusConfig['class'] }}">
                {{ $statusConfig['label'] }}
            </span>
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100 mb-6">
        <p class="text-gray-400 text-sm">Belum ada kelas.</p>
    </div>
@endforelse

{{-- Kelas Tersedia --}}
<h2 class="font-semibold text-gray-700 mb-3 mt-8">Kelas Tersedia ({{ $available->count() }})</h2>

@forelse($available as $batch)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-3">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-semibold text-gray-800">{{ $batch->name }}</p>
                <p class="text-sm text-gray-500">{{ $batch->program->name }}</p>
                @if($batch->start_date)
                    <p class="text-xs text-gray-400 mt-1">Mulai: {{ $batch->start_date->format('d M Y') }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('peserta.kelas.daftar') }}">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <button type="submit"
                        class="shrink-0 {{ $user->hasCompleteProfile() ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed' }}
                               text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors"
                        {{ $user->hasCompleteProfile() ? '' : 'disabled' }}>
                    Daftar
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl p-8 text-center shadow-sm border border-gray-100">
        <p class="text-gray-400 text-sm">Tidak ada kelas tersedia saat ini.</p>
    </div>
@endforelse

@endsection
