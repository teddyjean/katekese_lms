@extends('layouts.app')
@section('title', 'Test')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Test</h1>
    <p class="text-gray-500 text-sm mt-1">Test yang tersedia dari kelas Anda</p>
</div>

@forelse($tests as $test)
@php $attempt = $test->attempts->first(); @endphp
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3">
    <div class="flex justify-between items-start">
        <div>
            <p class="font-medium text-gray-800">{{ $test->title }}</p>
            <p class="text-xs text-blue-600 mt-1">{{ $test->batch->name }}</p>
            <div class="flex gap-3 mt-2 text-xs text-gray-500">
                <span>{{ $test->questions_count }} soal</span>
                @if($test->duration_minutes)
                    <span>&middot; {{ $test->duration_minutes }} menit</span>
                @else
                    <span>&middot; Tanpa batas waktu</span>
                @endif
            </div>
            @if($test->description)
                <p class="text-sm text-gray-500 mt-2">{{ Str::limit($test->description, 100) }}</p>
            @endif
        </div>
        <div class="ml-4 shrink-0">
            @if($attempt && $attempt->isSubmitted())
                <div class="text-center">
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-700 block mb-1">Selesai</span>
                    <a href="{{ route('peserta.test.result', $test) }}" class="text-xs text-blue-600 hover:underline">Lihat hasil</a>
                </div>
            @elseif($attempt)
                <a href="{{ route('peserta.test.show', $test) }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium px-4 py-2 rounded-lg transition-colors">Lanjutkan</a>
            @else
                <a href="{{ route('peserta.test.show', $test) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-4 py-2 rounded-lg transition-colors">Mulai Test</a>
            @endif
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-xl p-10 text-center text-gray-400 shadow-sm border border-gray-100">
    Tidak ada test aktif saat ini.
</div>
@endforelse

@if($tests->hasPages())
    <div class="mt-4">{{ $tests->links() }}</div>
@endif
@endsection
