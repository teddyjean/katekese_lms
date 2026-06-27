@extends('layouts.app')
@section('title', 'Penilaian Materi')
@section('content')

<div class="mb-6">
    <a href="{{ route('admin.materials.show', $material) }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali ke Materi</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Penilaian Materi: {{ $material->title }}</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $material->batch->name }} &middot; Skor Penguasaan/Tugas/Akhir dipilih manual oleh katekis (A/B/C). Skor numerik di bawah hanya referensi.</p>
</div>

@if($students->isEmpty())
<div class="bg-white rounded-2xl p-10 text-center shadow-sm border border-gray-100 text-gray-400">
    Belum ada peserta yang disetujui di kelas ini.
</div>
@else
<form method="POST" action="{{ route('admin.materials.assessments.update', $material) }}">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Siswa</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Ref. Test</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Ref. Tugas</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Penguasaan</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Tugas</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Catatan Aktivitas</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-semibold text-xs uppercase tracking-wide">Skor Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($students as $student)
                @php
                    $assessment = $assessments->get($student->id);
                    $refTest = $testScores->get($student->id, collect())->pluck('score')->filter(fn($s) => $s !== null)->implode(', ');
                    $refTugas = $submissionGrades->get($student->id, collect())->pluck('grade')->filter(fn($g) => $g !== null)->implode(', ');
                @endphp
                <tr class="hover:bg-gray-50/80 transition-colors align-top">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $student->name }}</p>
                        <p class="text-xs text-gray-400">{{ $student->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $refTest !== '' ? $refTest : '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $refTugas !== '' ? $refTugas : '-' }}</td>
                    <td class="px-4 py-3">
                        <select name="assessments[{{ $student->id }}][skor_penguasaan]"
                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-</option>
                            @foreach(['A', 'B', 'C'] as $grade)
                                <option value="{{ $grade }}" @selected(old("assessments.$student->id.skor_penguasaan", $assessment?->skor_penguasaan) === $grade)>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <select name="assessments[{{ $student->id }}][skor_tugas]"
                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-</option>
                            @foreach(['A', 'B', 'C'] as $grade)
                                <option value="{{ $grade }}" @selected(old("assessments.$student->id.skor_tugas", $assessment?->skor_tugas) === $grade)>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3 min-w-[220px]">
                        <textarea name="assessments[{{ $student->id }}][catatan_aktivitas]" rows="2"
                                  placeholder="Keaktifan, sikap, antusiasme, kesiapan mental, dll."
                                  class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old("assessments.$student->id.catatan_aktivitas", $assessment?->catatan_aktivitas) }}</textarea>
                    </td>
                    <td class="px-4 py-3">
                        <select name="assessments[{{ $student->id }}][skor_akhir]"
                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-</option>
                            @foreach(['A', 'B', 'C'] as $grade)
                                <option value="{{ $grade }}" @selected(old("assessments.$student->id.skor_akhir", $assessment?->skor_akhir) === $grade)>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
                Simpan Semua Penilaian
            </button>
        </div>
    </div>
</form>
@endif

@endsection
