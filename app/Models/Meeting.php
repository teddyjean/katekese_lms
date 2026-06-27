<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = ['batch_id', 'module_id', 'material_id', 'title', 'scheduled_at', 'location', 'notes'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function checkinOpensAt()
    {
        return $this->scheduled_at;
    }

    public function checkinClosesAt()
    {
        return $this->scheduled_at->copy()->addHours(2);
    }

    public function isCheckinOpen(): bool
    {
        return now()->between($this->scheduled_at, $this->checkinClosesAt());
    }

    public function canKatekisEdit(): bool
    {
        return now()->greaterThan($this->checkinClosesAt());
    }
}
