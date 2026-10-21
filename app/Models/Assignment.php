<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['batch_id', 'material_id', 'created_by', 'title', 'description', 'deadline', 'max_score'];

    protected $casts = ['deadline' => 'datetime'];

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

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function submissionByUser(int $userId): ?AssignmentSubmission
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
}
