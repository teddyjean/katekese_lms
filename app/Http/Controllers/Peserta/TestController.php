<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');

        $tests = Test::with([
            'batch',
            'attempts' => fn($q) => $q->where('user_id', $user->id),
        ])
            ->whereIn('batch_id', $batchIds)
            ->where('is_active', true)
            ->withCount('questions')
            ->orderByDesc('id')
            ->paginate(20);

        return view('peserta.test.index', compact('tests'));
    }

    public function show(Test $test)
    {
        $user = auth()->user();
        $batchIds = $user->approvedBatchesAsPeserta()->pluck('batches.id');
        abort_unless($batchIds->contains($test->batch_id) && $test->is_active, 403);

        $attempt = $test->attemptByUser($user->id);

        if ($attempt && $attempt->isSubmitted()) {
            return redirect()->route('peserta.test.result', $test);
        }

        if (!$attempt) {
            $attempt = TestAttempt::create([
                'test_id'    => $test->id,
                'user_id'    => $user->id,
                'started_at' => now(),
            ]);
        }

        $test->load('questions.options');
        $answers = $attempt->answers()->pluck('selected_option_id', 'question_id');

        return view('peserta.test.show', compact('test', 'attempt', 'answers'));
    }

    public function submit(Request $request, Test $test)
    {
        $user = auth()->user();
        $attempt = $test->attemptByUser($user->id);
        abort_unless($attempt && !$attempt->isSubmitted(), 403);

        $test->load('questions.options');
        $earnedPoints = 0;
        $totalPoints = 0;

        foreach ($test->questions as $question) {
            $answer = $request->input("answers.{$question->id}");

            if ($question->type === 'multiple_choice') {
                $correctOption = $question->options->firstWhere('is_correct', true);
                $isCorrect = $correctOption && $answer == $correctOption->id;
                $points = $isCorrect ? $question->points : 0;

                TestAnswer::create([
                    'attempt_id'         => $attempt->id,
                    'question_id'        => $question->id,
                    'selected_option_id' => $answer,
                    'is_correct'         => $isCorrect,
                    'points_earned'      => $points,
                ]);

                $earnedPoints += $points;
            } else {
                TestAnswer::create([
                    'attempt_id'    => $attempt->id,
                    'question_id'   => $question->id,
                    'answer_text'   => $answer,
                    'is_correct'    => null,
                    'points_earned' => null,
                ]);
            }

            $totalPoints += $question->points;
        }

        $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;

        $attempt->update([
            'submitted_at' => now(),
            'score'        => $score,
        ]);

        return redirect()->route('peserta.test.result', $test)->with('success', 'Test berhasil dikumpulkan.');
    }

    public function result(Test $test)
    {
        $user = auth()->user();
        $attempt = $test->attemptByUser($user->id);
        abort_unless($attempt && $attempt->isSubmitted(), 403);

        $attempt->load('answers.question.options', 'answers.selectedOption');
        return view('peserta.test.result', compact('test', 'attempt'));
    }
}
