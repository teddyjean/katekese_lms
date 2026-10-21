<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['batch_id', 'user_id', 'certificate_number', 'file_path', 'issued_at'];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
