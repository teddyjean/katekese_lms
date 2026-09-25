<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantNote extends Model
{
    protected $fillable = ['batch_id', 'user_id', 'katekis_id', 'note'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function katekis()
    {
        return $this->belongsTo(User::class, 'katekis_id');
    }
}
