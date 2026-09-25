<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['batch_id', 'created_by', 'title', 'description', 'type', 'status'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(FormQuestion::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }
}
