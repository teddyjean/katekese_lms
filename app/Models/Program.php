<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name', 'description', 'status', 'order'];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function katekis()
    {
        return $this->belongsToMany(User::class, 'program_katekis');
    }
}
