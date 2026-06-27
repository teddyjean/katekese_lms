@extends('layouts.app')
@section('title', 'Hasil Test')
@section('content')
<div class="mb-6">
    <a href="{{ route('peserta.test.index') }}" class="text-sm text-gray-500 hover:text-blue-600">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $test->title }}</h1>
    <p class="text-gray-500 text-sm mt-1">Hasil Test</p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 mb-6 text-center">
        <p class="text-gray-500 text-sm mb-2">Skor Anda</p>
        <p class="text-6xl font-bold {{ $attempt->score >= 70 ? 'text-blue-600' : 'text-red-500' }}">
            {{ $attempt->score }}%
        </p>
        <p class="text-gray-400 text-sm mt-3">Diselesaikan: {{ $attempt->submitted_at->format('d M Y H:i') }}</p>
        @if($attempt->score >= 70)
            <p class="text-green-600 font-medium mt-2">Selamat! Anda lulus.</p>
        @else
            <p class="text-orange-600 font-medium mt-2">Pelajari kembali materi dan coba tingkatkan.</p>
        @endif
    </div>

    <div class="space-y-4">
        <h2 class="font-semibold text-gray-700">Rincian Jawaban</h2>
        @foreach($attempt->answers as $i => $answer)
        @php $question = $answer->question; @endphp
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="font-medium text-gray-800 mb-3">
                {{ $i + 1 }}. {{ $question->question_text }}
                <span class="text-xs text-gray-400 font-normal">({{ $question->points }} poin)</span>
            </p>
            @if($question->type === 'multiple_choice')
                <div class="space-y-1.5">
                    @foreach($question->options as $j => $opt)
                    <p class="text-sm flex items-center gap-2
                        @if($opt->is_correct) text-green-700 font-medium
                        @elseif($answer->selected_option_id == $opt->id && !$opt->is_correct) text-red-500
                        @else text-gray-500 @endif">
                        @if($opt->is_correct)
                            <span class="text-green-500">✓</span>
                        @elseif($answer->selected_option_id == $opt->id)
                            <span class="text-red-500">✗</span>
                        @else
                            <span class="w-3"></span>
                        @endif
                        {{ chr(65 + $j) }}. {{ $opt->option_text }}
                    </p>
                    @endforeach
                </div>
                <p class="text-xs mt-3 font-medium {{ $answer->is_correct ? 'text-green-600' : 'text-red-500' }}">
                    {{ $answer->is_correct ? '+' . $question->points . ' poin' : '0 poin' }}
                </p>
            @else
                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
                    <p class="text-xs text-gray-400 mb-1">Jawaban Anda:</p>
                    <p>{{ $answer->answer_text ?? '(tidak dijawab)' }}</p>
                </div>
                <p class="text-xs mt-2 text-gray-400 italic">Soal esai akan dinilai oleh katekis</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
