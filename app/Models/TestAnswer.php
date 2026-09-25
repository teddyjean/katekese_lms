<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $fillable = [
        'attempt_id', 'question_id', 'selected_option_id',
        'answer_text', 'is_correct', 'points_earned',
    ];

    public function attempt()
    {
        return $this->belongsTo(TestAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}
