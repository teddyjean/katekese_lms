@extends('layouts.app')
@section('title', 'Kelola Soal')
@section('content')
<div class="mb-6 flex items-start justify-between flex-wrap gap-y-3">
    <div>
        <a href="{{ route('admin.batches.show', $test->batch_id) }}?tab=test" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $test->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $test->batch->name }} &middot; {{ $test->questions->count() }} soal &middot; Total: {{ $test->totalPoints() }} poin</p>
    </div>
    <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <a href="{{ route('admin.tests.edit', $test) }}" class="text-sm text-gray-500 hover:text-blue-600 border border-gray-200 px-3 py-2 rounded-lg">Edit</a>
        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $test->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
            {{ $test->is_active ? 'Aktif' : 'Draft' }}
        </span>
        <form method="POST" action="{{ route('admin.tests.toggle-active', $test) }}">
            @csrf @method('PATCH')
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg transition-colors">
                {{ $test->is_active ? 'Nonaktifkan' : 'Aktifkan Test' }}
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Form Tambah Soal --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-700 mb-4">Tambah Soal</h2>
        <form method="POST" action="{{ route('admin.tests.questions.store', $test) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                <textarea name="question_text" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('question_text') border-red-400 @enderror">{{ old('question_text') }}</textarea>
                @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" id="questionType" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" onchange="toggleOptions()">
                        <option value="multiple_choice" @selected(old('type') === 'multiple_choice')>Pilihan Ganda</option>
                        <option value="essay" @selected(old('type') === 'essay')>Esai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poin</label>
                    <input type="number" name="points" value="{{ old('points', 1) }}" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            <div id="optionsSection" class="space-y-2">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Pilihan Jawaban</label>
                    <span class="text-xs text-gray-400">Centang <span class="text-emerald-600 font-medium">✓ Benar</span> pada jawaban yang tepat</span>
                </div>
                @foreach(range(0, 3) as $i)
                <div class="option-row flex items-center gap-2 rounded-lg px-2 py-1 transition-colors {{ old('correct') == $i ? 'bg-emerald-50 border border-emerald-200' : 'border border-transparent' }}">
                    <label class="flex items-center gap-1.5 shrink-0 cursor-pointer select-none">
                        <input type="radio" name="correct" value="{{ $i }}"
                               @checked(old('correct') == $i)
                               class="accent-emerald-600 w-4 h-4 cursor-pointer"
                               onchange="highlightCorrect(this)">
                        <span class="text-xs font-semibold text-gray-500">{{ chr(65 + $i) }}</span>
                    </label>
                    <input type="text" name="options[]" value="{{ old('options.' . $i) }}"
                           placeholder="Tulis pilihan {{ chr(65 + $i) }}..."
                           class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                @endforeach
                @error('options') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('correct') <p class="text-red-500 text-xs mt-1">Pilih jawaban yang benar.</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">Tambah Soal</button>
        </form>
    </div>

    {{-- Daftar Soal --}}
    <div class="space-y-3">
        <h2 class="font-semibold text-gray-700">Daftar Soal ({{ $test->questions->count() }})</h2>
        @forelse($test->questions as $i => $question)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $i + 1 }}. {{ $question->question_text }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                            {{ $question->type === 'multiple_choice' ? 'Pilihan Ganda' : 'Esai' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $question->points }} poin</span>
                    </div>
                    @if($question->type === 'multiple_choice')
                    <div class="mt-2 space-y-1">
                        @foreach($question->options as $j => $opt)
                        <p class="text-xs {{ $opt->is_correct ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            {{ chr(65 + $j) }}. {{ $opt->option_text }}{{ $opt->is_correct ? ' ✓' : '' }}
                        </p>
                        @endforeach
                    </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.tests.questions.destroy', [$test, $question]) }}" onsubmit="return confirm('Hapus soal ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-600 mt-1 shrink-0">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
            Belum ada soal. Tambahkan dari form di sebelah kiri.
        </div>
        @endforelse
    </div>

</div>

@if($test->attempts->count())
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <h2 class="font-semibold text-gray-700 mb-4">
        Hasil Siswa
        <span class="text-gray-400 font-normal text-sm">({{ $test->attempts->where('submitted_at', '!=', null)->count() }} selesai)</span>
    </h2>
    <div class="overflow-x-auto -mx-5">
    <table class="w-full min-w-[480px] text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-2 text-gray-600 font-semibold">Siswa</th>
                <th class="text-left px-4 py-2 text-gray-600 font-semibold">Mulai</th>
                <th class="text-left px-4 py-2 text-gray-600 font-semibold">Selesai</th>
                <th class="text-left px-4 py-2 text-gray-600 font-semibold">Skor</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($test->attempts as $attempt)
            <tr>
                <td class="px-4 py-2 text-gray-800">{{ $attempt->user->name }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $attempt->started_at->format('d M Y H:i') }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y H:i') : 'Belum selesai' }}</td>
                <td class="px-4 py-2 font-bold {{ $attempt->score !== null ? 'text-blue-700' : 'text-gray-400' }}">
                    {{ $attempt->score !== null ? $attempt->score . '%' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endif

<script>
function toggleOptions() {
    const type = document.getElementById('questionType').value;
    document.getElementById('optionsSection').style.display = type === 'multiple_choice' ? 'block' : 'none';
}
toggleOptions();

function highlightCorrect(radio) {
    document.querySelectorAll('.option-row').forEach(function(row) {
        row.classList.remove('bg-emerald-50', 'border-emerald-200');
        row.classList.add('border-transparent');
    });
    const row = radio.closest('.option-row');
    row.classList.add('bg-emerald-50', 'border-emerald-200');
    row.classList.remove('border-transparent');
}
</script>
@endsection
