@extends('layouts.app')
@section('title', $assignment->title)
@section('content')
<div class="mb-6">
    <a href="{{ route('peserta.tugas.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $assignment->title }}</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $assignment->batch->name }}</p>
</div>

<div class="max-w-3xl space-y-5">
    {{-- Info tugas --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between text-sm mb-3 text-gray-500">
            <span>Nilai Maksimum: <strong class="text-gray-800">{{ $assignment->max_score }}</strong></span>
            @if($assignment->deadline)
                <span class="{{ $assignment->deadline->isPast() ? 'text-red-500 font-medium' : '' }}">
                    Deadline: {{ $assignment->deadline->format('d M Y H:i') }}
                </span>
            @endif
        </div>
        @if($assignment->description)
            <div class="border-t border-gray-50 pt-3">
                <p class="text-gray-700 whitespace-pre-line text-sm leading-relaxed">{{ $assignment->description }}</p>
            </div>
        @endif
    </div>

    @if($submission)
        {{-- Info submission --}}
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="font-semibold text-green-800 mb-3">Tugas sudah dikumpulkan</p>
            <div class="space-y-1 text-sm text-green-700">
                <p>File: <strong>{{ $submission->file_original_name }}</strong></p>
                <p>Waktu: {{ $submission->submitted_at->format('d M Y H:i') }}</p>
                @if($submission->notes)
                    <p>Catatan: {{ $submission->notes }}</p>
                @endif
            </div>
            @if($submission->grade !== null)
                <div class="mt-4 pt-4 border-t border-green-200">
                    <p class="text-xl font-bold text-blue-800">Nilai: {{ $submission->grade }} / {{ $assignment->max_score }}</p>
                    @if($submission->feedback)
                        <p class="text-sm text-gray-700 mt-2 bg-white rounded-lg p-3">Feedback: {{ $submission->feedback }}</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-green-600 mt-3 italic">Menunggu penilaian dari katekis...</p>
            @endif
        </div>

        {{-- Preview file submission --}}
        @php
            $ext      = strtolower(pathinfo($submission->file_original_name, PATHINFO_EXTENSION));
            $fileUrl  = Storage::url($submission->file_path);
            $absUrl   = asset($fileUrl);
            $isImage  = in_array($ext, ['jpg', 'jpeg', 'png']);
            $isPdf    = $ext === 'pdf';
            $isVideo  = $ext === 'mp4';
            $isOffice = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $submission->file_original_name }}</p>
                    <span class="text-xs font-semibold uppercase tracking-wide mt-0.5
                        @if($isPdf) text-red-500
                        @elseif($isImage) text-green-500
                        @elseif($isVideo) text-purple-500
                        @elseif($isOffice) text-blue-500
                        @else text-gray-400 @endif">{{ $ext }}</span>
                </div>
                <a href="{{ $fileUrl }}" download="{{ $submission->file_original_name }}"
                   class="shrink-0 inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download
                </a>
            </div>

            @if($isPdf)
                <iframe src="{{ $fileUrl }}" class="w-full" style="height: 75vh;" frameborder="0"></iframe>
            @elseif($isImage)
                <div class="p-6 flex justify-center bg-gray-50">
                    <img src="{{ $fileUrl }}" alt="{{ $submission->file_original_name }}" class="max-w-full rounded-lg shadow-sm">
                </div>
            @elseif($isVideo)
                <div class="bg-black flex justify-center p-4">
                    <video controls class="max-w-full rounded" style="max-height: 70vh;">
                        <source src="{{ $fileUrl }}" type="video/mp4">
                    </video>
                </div>
            @elseif($isOffice)
                <div class="relative" style="height: 75vh;">
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($absUrl) }}"
                            class="w-full h-full" frameborder="0"></iframe>
                    <p class="absolute bottom-2 right-3 text-xs text-gray-300">Powered by Microsoft Office Online</p>
                </div>
            @else
                <div class="py-12 text-center text-gray-400">
                    <p class="text-sm">Preview tidak tersedia untuk tipe file <strong>.{{ $ext }}</strong></p>
                </div>
            @endif
        </div>

    @else
        @php $isLate = $assignment->deadline && $assignment->deadline->isPast(); @endphp
        @if($isLate)
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                Deadline sudah lewat. Hubungi katekis jika masih perlu mengumpulkan.
            </div>
        @endif
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h2 class="font-semibold text-gray-700 mb-4">Kumpulkan Tugas</h2>
            <form method="POST" action="{{ route('peserta.tugas.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                    <input type="file" name="file" class="w-full text-sm text-gray-600 border border-gray-200 rounded-lg px-3 py-2 @error('file') border-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">PDF, Word, atau gambar. Maks 10MB.</p>
                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan <span class="text-gray-400">(opsional)</span></label>
                    <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                    Kumpulkan Tugas
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
