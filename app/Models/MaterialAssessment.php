<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Sumber data untuk Raport per batch nanti:
 * MaterialAssessment::whereIn('material_id', $batch->materials->pluck('id'))->where('user_id', $studentId)->get()
 */
class MaterialAssessment extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'skor_penguasaan',
        'skor_tugas',
        'catatan_aktivitas',
        'skor_akhir',
        'assessed_by',
        'assessed_at',
    ];

    protected $casts = [
        'assessed_at' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
