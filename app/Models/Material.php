<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['batch_id', 'uploaded_by', 'title', 'description', 'file_path', 'file_original_name', 'video_url', 'order'];

    public function videoEmbedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)([\w-]+)/', $this->video_url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return $this->video_url;
    }

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
