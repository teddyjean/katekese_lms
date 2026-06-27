@extends('layouts.app')
@section('title', 'Materi')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Materi</h1>
    <p class="text-gray-500 text-sm mt-1">Materi pembelajaran dari kelas Anda</p>
</div>

@forelse($materials as $material)
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-3 hover:border-blue-200 transition-colors">
    <div class="flex justify-between items-start gap-4">
        <div class="min-w-0">
            <p class="font-medium text-gray-800">{{ $material->title }}</p>
            <p class="text-xs text-blue-600 mt-1">{{ $material->batch->name }}</p>
            @if($material->description)
                <p class="text-sm text-gray-500 mt-2">{{ Str::limit($material->description, 120) }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hidden sm:inline">{{ $material->file_original_name }}</span>
            <a href="{{ route('peserta.materi.show', $material) }}"
               class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg transition-colors">
                Lihat
            </a>
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-xl p-10 text-center text-gray-400 shadow-sm border border-gray-100">
    Belum ada materi. Pastikan Anda sudah terdaftar di kelas.
</div>
@endforelse

@if($materials->hasPages())
    <div class="mt-4">{{ $materials->links() }}</div>
@endif
@endsection
