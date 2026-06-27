@extends('layouts.app')
@section('title', $material->title)
@section('content')

@php
    $ext        = strtolower(pathinfo($material->file_original_name, PATHINFO_EXTENSION));
    $fileUrl    = Storage::url($material->file_path);
    $absUrl     = asset($fileUrl);
    $isImage    = in_array($ext, ['jpg', 'jpeg', 'png']);
    $isPdf      = $ext === 'pdf';
    $isVideo    = $ext === 'mp4';
    $isOffice   = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
@endphp

{{-- Breadcrumb + judul --}}
<div class="mb-6">
    <a href="{{ route('peserta.materi.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Materi</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $material->title }}</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $material->batch->name }}</p>
</div>

{{-- Deskripsi --}}
@if($material->description)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl mb-5">
    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $material->description }}</p>
</div>
@endif

{{-- Viewer --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">

    {{-- Header bar --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
        <div class="min-w-0">
            <p class="text-sm font-medium text-gray-700 truncate">{{ $material->file_original_name }}</p>
            <span class="inline-block text-xs font-semibold uppercase tracking-wide mt-0.5
                @if($isPdf) text-red-500
                @elseif($isImage) text-green-500
                @elseif($isVideo) text-purple-500
                @elseif($isOffice) text-blue-500
                @else text-gray-400
                @endif">
                {{ $ext }}
            </span>
        </div>
        <a href="{{ $fileUrl }}" download="{{ $material->file_original_name }}"
           class="shrink-0 inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Download
        </a>
    </div>

    {{-- Preview area --}}
    @if($isPdf)
        <iframe src="{{ $fileUrl }}" class="w-full" style="height: 78vh;" frameborder="0"></iframe>

    @elseif($isImage)
        <div class="p-6 flex justify-center bg-gray-50">
            <img src="{{ $fileUrl }}" alt="{{ $material->title }}" class="max-w-full rounded-lg shadow-sm">
        </div>

    @elseif($isVideo)
        <div class="bg-black flex justify-center p-4">
            <video controls class="max-w-full rounded" style="max-height: 72vh;">
                <source src="{{ $fileUrl }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>

    @elseif($isOffice)
        <div class="relative" style="height: 78vh;">
            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($absUrl) }}"
                    class="w-full h-full" frameborder="0">
            </iframe>
            <p class="absolute bottom-2 right-3 text-xs text-gray-300">Powered by Microsoft Office Online</p>
        </div>

    @else
        <div class="py-16 text-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <p class="text-sm">Preview tidak tersedia untuk tipe file <strong>.{{ $ext }}</strong></p>
            <p class="text-xs mt-1">Gunakan tombol Download di atas untuk membuka file.</p>
        </div>
    @endif

</div>

@endsection
