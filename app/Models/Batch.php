<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['program_id', 'name', 'start_date', 'end_date', 'description', 'status', 'nama_romo', 'tanggal_sakramen'];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'tanggal_sakramen' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function katekis()
    {
        return $this->belongsToMany(User::class, 'batch_katekis');
    }

    public function peserta()
    {
        return $this->belongsToMany(User::class, 'batch_participants')
            ->withPivot('joined_at', 'status', 'rejection_note')
            ->withTimestamps();
    }

    public function approvedPeserta()
    {
        return $this->belongsToMany(User::class, 'batch_participants')
            ->withPivot('joined_at', 'status', 'rejection_note', 'lulus')
            ->withTimestamps()
            ->wherePivot('status', 'approved');
    }

    public function pendingPeserta()
    {
        return $this->belongsToMany(User::class, 'batch_participants')
            ->withPivot('joined_at', 'status', 'rejection_note')
            ->withTimestamps()
            ->wherePivot('status', 'pending');
    }

    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderByDesc('deadline');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class)->orderByDesc('scheduled_at');
    }

    /**
     * Advisory-only eligibility note for a pending peserta, surfaced to the
     * katekis at approval time. Never blocks enrollment — sacraments received
     * at another parish aren't tracked in this system, so the katekis (who has
     * real-world context) makes the final call, not this check.
     */
    public function eligibilityWarningFor(User $user): ?string
    {
        $program = $this->program;

        if (! $program->order || $program->order <= 1) {
            return null;
        }

        $profile = $user->profile;
        if (! $profile || ! $profile->nama_baptis) {
            return 'Belum mengisi Nama Baptis di profil — pastikan siswa sudah dibaptis sebelum menyetujui.';
        }

        $prevProgram = Program::where('order', $program->order - 1)->first();
        if ($prevProgram) {
            $hasRecordedGraduation = $user->batchesAsPeserta()
                ->wherePivot('status', 'approved')
                ->where('batches.status', 'completed')
                ->where('batches.program_id', $prevProgram->id)
                ->exists();

            if (! $hasRecordedGraduation) {
                return "Belum ada riwayat lulus {$prevProgram->name} di sistem ini — mungkin sakramen sebelumnya diterima di paroki lain. Konfirmasi ke siswa/orang tua sebelum menyetujui.";
            }
        }

        return null;
    }
}
