<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
    /**
     * Katekis hanya boleh mengubah kelas yang mereka ajar; katekis lain
     * hanya bisa melihat.
     */
    public function manage(User $user, Batch $batch): bool
    {
        return $user->teachesBatch($batch);
    }
}
