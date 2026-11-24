<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
    /**
     * Administrator boleh mengelola kelas apapun walau bukan pengajarnya.
     * Katekis hanya boleh mengubah kelas yang mereka ajar.
     */
    public function manage(User $user, Batch $batch): bool
    {
        return $user->isAdministrator() || $user->teachesBatch($batch);
    }

    /**
     * Hanya Administrator yang boleh menghapus kelas secara permanen.
     */
    public function delete(User $user, Batch $batch): bool
    {
        return $user->isAdministrator();
    }
}
