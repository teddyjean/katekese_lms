<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    protected $fillable = ['batch_id', 'uploaded_by', 'title', 'description', 'file_path', 'file_original_name', 'file_disk', 'video_url', 'order'];

    public function previewUrl(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        if ($this->file_disk === 'b2') {
            return Storage::disk('b2')->temporaryUrl(
                $this->file_path,
                now()->addMinutes(60),
                ['ResponseContentDisposition' => 'inline; filename="' . $this->file_original_name . '"']
            );
        }

        return Storage::url($this->file_path);
    }

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
