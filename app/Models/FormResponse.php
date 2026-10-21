<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $fillable = ['form_id', 'user_id', 'score', 'feedback', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(FormAnswer::class);
    }
}
