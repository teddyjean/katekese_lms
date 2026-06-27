<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['batch_id', 'uploaded_by', 'title', 'description', 'file_path', 'file_original_name', 'order'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function assessments()
    {
        return $this->hasMany(MaterialAssessment::class);
    }
}
