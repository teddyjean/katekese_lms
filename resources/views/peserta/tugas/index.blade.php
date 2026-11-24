@extends('layouts.app')
@section('title', 'Tugas')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Tugas</h1>
    <p class="text-gray-500 text-sm mt-1">Daftar tugas dari kelas Anda</p>
</div>

@forelse($assignments as $assignment)
@php $sub = $assignment->submissions->first(); @endphp
<a href="{{ route('peserta.tugas.show', $assignment) }}" class="block bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3 hover:border-blue-200 transition-colors">
    <div class="flex justify-between items-start">
        <div>
            <p class="font-medium text-gray-800">{{ $assignment->title }}</p>
            <p class="text-xs text-blue-600 mt-1">{{ $assignment->batch->name }}</p>
            @if($assignment->deadline)
                <p class="text-xs mt-1 {{ $assignment->deadline->isPast() && !$sub ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                    Deadline: {{ $assignment->deadline->format('d M Y H:i') }}
                    @if($assignment->deadline->isPast() && !$sub) (lewat) @endif
                </p>
            @endif
        </div>
        <div class="ml-4 shrink-0">
            @if($sub)
                @if($sub->grade !== null)
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-700">Nilai: {{ $sub->grade }}</span>
                @else
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-700">Dikumpulkan</span>
                @endif
            @else
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-orange-100 text-orange-700">Belum Dikumpulkan</span>
            @endif
        </div>
    </div>
</a>
@empty
<div class="bg-white rounded-xl p-10 text-center text-gray-400 shadow-sm border border-gray-100">
    Belum ada tugas.
</div>
@endforelse

@if($assignments->hasPages())
    <div class="mt-4">{{ $assignments->links() }}</div>
@endif
@endsection
