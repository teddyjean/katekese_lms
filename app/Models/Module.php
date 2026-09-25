<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['batch_id', 'title', 'description', 'order'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
