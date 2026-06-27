<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    protected $fillable = ['form_response_id', 'form_question_id', 'answer_text', 'file_path'];

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'form_question_id');
    }
}
