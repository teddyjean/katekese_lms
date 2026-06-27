<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'is_active',
        'password',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function hasCompleteProfile(): bool
    {
        return $this->profile !== null;
    }

    public function enrollmentEligibleProgramOrder(): int
    {
        $profile = $this->profile;

        if (! $profile || empty($profile->gereja_baptis) || empty($profile->nomor_buku_baptis)) {
            return 1;
        }

        if (empty($profile->gereja_komuni_pertama)) {
            return 2;
        }

        return 3;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'katekis';
    }

    public function isKatekis(): bool
    {
        return $this->role === 'katekis';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function batchesAsKatekis()
    {
        return $this->belongsToMany(Batch::class, 'batch_katekis');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_katekis');
    }

    public function batchesAsPeserta()
    {
        return $this->belongsToMany(Batch::class, 'batch_participants')
            ->withPivot('joined_at', 'status', 'rejection_note')
            ->withTimestamps();
    }

    public function approvedBatchesAsPeserta()
    {
        return $this->belongsToMany(Batch::class, 'batch_participants')
            ->withPivot('joined_at', 'status', 'rejection_note')
            ->withTimestamps()
            ->wherePivot('status', 'approved');
    }
}
