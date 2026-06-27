@extends('layouts.app')
@section('title', 'Submission Tugas')
@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <a href="{{ route('admin.batches.show', $assignment->batch_id) }}?tab=tugas" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $assignment->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">Kelas: {{ $assignment->batch->name }} &middot; Nilai maks: {{ $assignment->max_score }}</p>
    </div>
    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="text-sm text-gray-500 hover:text-blue-600 border border-gray-200 px-3 py-2 rounded-lg shrink-0">Edit</a>
</div>

@if($assignment->description)
<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $assignment->description }}</p>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-semibold text-gray-700">Daftar Submission</h2>
        <span class="text-sm text-gray-500">{{ $assignment->submissions->count() }} / {{ $peserta->count() }} peserta</span>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full min-w-[700px] text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-gray-600 font-semibold">Peserta</th>
                <th class="text-left px-5 py-3 text-gray-600 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-gray-600 font-semibold">Waktu Kumpul</th>
                <th class="text-left px-5 py-3 text-gray-600 font-semibold">Nilai</th>
                <th class="text-left px-5 py-3 text-gray-600 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($peserta as $p)
            @php
                $sub     = $assignment->submissions->firstWhere('user_id', $p->id);
                $ext     = $sub ? strtolower(pathinfo($sub->file_original_name, PATHINFO_EXTENSION)) : null;
                $fileUrl = $sub ? Storage::url($sub->file_path) : null;
                $absUrl  = $sub ? asset($fileUrl) : null;
                $isImage  = $sub && in_array($ext, ['jpg', 'jpeg', 'png']);
                $isPdf    = $sub && $ext === 'pdf';
                $isVideo  = $sub && $ext === 'mp4';
                $isOffice = $sub && in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $p->name }}</p>
                    <p class="text-xs text-gray-400">{{ $p->email }}</p>
                </td>
                <td class="px-5 py-3">
                    @if($sub)
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-700">Sudah Kumpul</span>
                    @else
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-500">Belum</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-600 text-xs">
                    {{ $sub ? $sub->submitted_at->format('d M Y H:i') : '-' }}
                </td>
                <td class="px-5 py-3">
                    @if($sub)
                        <span class="{{ $sub->grade !== null ? 'font-bold text-blue-700' : 'text-gray-400 text-xs' }}">
                            {{ $sub->grade !== null ? $sub->grade : 'Belum dinilai' }}
                        </span>
                    @else
                        <span class="text-gray-300">-</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if($sub)
                        <div class="flex items-center gap-3 flex-wrap">
                            <button onclick="togglePreview('preview-{{ $sub->id }}')"
                                    class="text-xs text-indigo-600 hover:underline">Preview</button>
                            <a href="{{ $fileUrl }}" download="{{ $sub->file_original_name }}"
                               class="text-xs text-green-600 hover:underline">Unduh</a>
                            <form method="POST" action="{{ route('admin.submissions.grade', $sub) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <input type="number" name="grade" value="{{ $sub->grade }}" min="0" max="{{ $assignment->max_score }}" step="0.5"
                                       class="w-20 border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-300"
                                       placeholder="Nilai">
                                <input type="text" name="feedback" value="{{ $sub->feedback }}"
                                       class="w-40 border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-300"
                                       placeholder="Feedback (opsional)">
                                <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Simpan</button>
                            </form>
                        </div>
                    @endif
                </td>
            </tr>

            {{-- Preview row --}}
            @if($sub)
            <tr id="preview-{{ $sub->id }}" class="hidden bg-gray-50">
                <td colspan="5" class="px-5 py-4">
                    <div class="rounded-xl border border-gray-200 overflow-hidden bg-white">
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $sub->file_original_name }}</p>
                                <span class="text-xs font-semibold uppercase tracking-wide
                                    @if($isPdf) text-red-500
                                    @elseif($isImage) text-green-500
                                    @elseif($isVideo) text-purple-500
                                    @elseif($isOffice) text-blue-500
                                    @else text-gray-400 @endif">{{ $ext }}</span>
                            </div>
                            <button onclick="togglePreview('preview-{{ $sub->id }}')"
                                    class="text-xs text-gray-400 hover:text-gray-600">Tutup</button>
                        </div>

                        @if($isPdf)
                            <iframe src="{{ $fileUrl }}" class="w-full" style="height: 70vh;" frameborder="0"></iframe>
                        @elseif($isImage)
                            <div class="p-6 flex justify-center bg-gray-50">
                                <img src="{{ $fileUrl }}" alt="{{ $sub->file_original_name }}" class="max-w-full rounded-lg shadow-sm" style="max-height: 70vh;">
                            </div>
                        @elseif($isVideo)
                            <div class="bg-black flex justify-center p-4">
                                <video controls class="max-w-full rounded" style="max-height: 65vh;">
                                    <source src="{{ $fileUrl }}" type="video/mp4">
                                </video>
                            </div>
                        @elseif($isOffice)
                            <div class="relative" style="height: 70vh;">
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
                </td>
            </tr>
            @endif

            @endforeach
        </tbody>
    </table>
    </div>
</div>

<script>
function togglePreview(id) {
    const row = document.getElementById(id);
    row.classList.toggle('hidden');
}
</script>
@endsection
