<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = ['batch_id', 'material_id', 'created_by', 'title', 'description', 'duration_minutes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    public function attemptByUser(int $userId): ?TestAttempt
    {
        return $this->attempts()->where('user_id', $userId)->first();
    }

    public function totalPoints(): int
    {
        return $this->questions->sum('points');
    }
}
