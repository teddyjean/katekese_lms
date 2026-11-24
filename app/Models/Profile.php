<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'nama_baptis',
        'alamat',
        'nama_ayah',
        'nama_ibu',
        'gereja_baptis',
        'nomor_buku_baptis',
        'gereja_komuni_pertama',
        'sekolah',
        'kelas',
        'tanggal_lahir',
        'wilayah',
        'lingkungan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
