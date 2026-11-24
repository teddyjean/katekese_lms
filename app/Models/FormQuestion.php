<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormQuestion extends Model
{
    protected $fillable = ['form_id', 'question', 'type', 'is_required', 'order'];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function options()
    {
        return $this->hasMany(FormOption::class, 'form_question_id')->orderBy('order');
    }

    public function answers()
    {
        return $this->hasMany(FormAnswer::class, 'form_question_id');
    }
}
