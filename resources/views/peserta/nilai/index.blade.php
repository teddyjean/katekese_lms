@extends('layouts.app')
@section('title', 'Nilai')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Nilai</h1>
    <p class="text-gray-500 text-sm mt-1">Rekap nilai tugas dan test Anda</p>
</div>

<h2 class="font-semibold text-gray-700 mb-3">Nilai Tugas</h2>
@forelse($assignments as $assignment)
@php $sub = $assignment->submissions->first(); @endphp
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3">
    <div class="flex justify-between items-start">
        <div>
            <p class="font-medium text-gray-800">{{ $assignment->title }}</p>
            <p class="text-xs text-blue-600 mt-0.5">{{ $assignment->batch->name }}</p>
            <p class="text-xs text-gray-400 mt-1">Dikumpulkan: {{ $sub->submitted_at->format('d M Y H:i') }}</p>
        </div>
        <div class="text-right ml-4 shrink-0">
            @if($sub->grade !== null)
                <p class="text-2xl font-bold text-blue-700">{{ $sub->grade }}</p>
                <p class="text-xs text-gray-400">dari {{ $assignment->max_score }}</p>
            @else
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-orange-100 text-orange-700">Belum dinilai</span>
            @endif
        </div>
    </div>
    @if($sub->feedback)
        <p class="text-xs text-gray-600 mt-3 bg-gray-50 rounded-lg p-3">
            <span class="font-medium">Feedback:</span> {{ $sub->feedback }}
        </p>
    @endif
</div>
@empty
<div class="bg-white rounded-xl p-6 text-center text-gray-400 shadow-sm border border-gray-100 mb-6">
    Belum ada nilai tugas.
</div>
@endforelse

<h2 class="font-semibold text-gray-700 mb-3 mt-8">Nilai Test</h2>
@forelse($tests as $test)
@php $attempt = $test->attempts->first(); @endphp
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3">
    <div class="flex justify-between items-start">
        <div>
            <p class="font-medium text-gray-800">{{ $test->title }}</p>
            <p class="text-xs text-blue-600 mt-0.5">{{ $test->batch->name }}</p>
            <p class="text-xs text-gray-400 mt-1">Selesai: {{ $attempt->submitted_at->format('d M Y H:i') }}</p>
        </div>
        <div class="text-right ml-4 shrink-0">
            <p class="text-2xl font-bold {{ $attempt->score >= 70 ? 'text-blue-700' : 'text-red-600' }}">
                {{ $attempt->score }}%
            </p>
            <p class="text-xs {{ $attempt->score >= 70 ? 'text-green-600' : 'text-orange-500' }}">
                {{ $attempt->score >= 70 ? 'Lulus' : 'Perlu ditingkatkan' }}
            </p>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('peserta.test.result', $test) }}" class="text-xs text-blue-600 hover:underline">Lihat detail jawaban &rarr;</a>
    </div>
</div>
@empty
<div class="bg-white rounded-xl p-6 text-center text-gray-400 shadow-sm border border-gray-100">
    Belum ada nilai test.
</div>
@endforelse
@endsection
