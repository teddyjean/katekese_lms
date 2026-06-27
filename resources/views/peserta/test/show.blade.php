@extends('layouts.app')
@section('title', $test->title)
@section('content')
<div class="mb-6 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $test->title }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $test->batch->name }}</p>
    </div>
    @if($test->duration_minutes)
        <div class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2 text-sm font-mono font-bold text-orange-700" id="timer">
            {{ $test->duration_minutes }}:00
        </div>
    @endif
</div>

@if($test->description)
<div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-800 leading-relaxed">
    {{ $test->description }}
</div>
@endif

<form method="POST" action="{{ route('peserta.test.submit', $test) }}" id="testForm">
    @csrf
    <div class="space-y-5 max-w-3xl">
        @foreach($test->questions as $i => $question)
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="font-medium text-gray-800 mb-3">
                {{ $i + 1 }}. {{ $question->question_text }}
                <span class="text-xs text-gray-400 font-normal ml-2">({{ $question->points }} poin)</span>
            </p>

            @if($question->type === 'multiple_choice')
                <div class="space-y-2">
                    @foreach($question->options as $j => $option)
                    <label class="flex items-center gap-3 cursor-pointer p-2.5 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                               @checked($answers->get($question->id) == $option->id)
                               class="shrink-0">
                        <span class="text-sm text-gray-700">{{ chr(65 + $j) }}. {{ $option->option_text }}</span>
                    </label>
                    @endforeach
                </div>
            @else
                <textarea name="answers[{{ $question->id }}]" rows="4"
                          placeholder="Tulis jawaban Anda di sini..."
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ $answers->get($question->id) }}</textarea>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-6 max-w-3xl flex justify-end">
        <button type="submit"
                onclick="return confirm('Yakin ingin mengumpulkan? Jawaban tidak bisa diubah setelah dikumpulkan.')"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-3 rounded-lg transition-colors">
            Kumpulkan Test
        </button>
    </div>
</form>

@if($test->duration_minutes)
<script>
const startedAt = new Date('{{ $attempt->started_at->toIso8601String() }}');
const totalSeconds = {{ $test->duration_minutes }} * 60;
const timerEl = document.getElementById('timer');

function updateTimer() {
    const elapsed = Math.floor((Date.now() - startedAt.getTime()) / 1000);
    const remaining = totalSeconds - elapsed;
    if (remaining <= 0) {
        timerEl.textContent = '00:00';
        document.getElementById('testForm').submit();
        return;
    }
    const m = Math.floor(remaining / 60);
    const s = remaining % 60;
    timerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    if (remaining <= 60) {
        timerEl.classList.remove('bg-orange-50', 'border-orange-200', 'text-orange-700');
        timerEl.classList.add('bg-red-50', 'border-red-300', 'text-red-700');
    }
}
setInterval(updateTimer, 1000);
updateTimer();
</script>
@endif
@endsection
