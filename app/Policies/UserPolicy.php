<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Hanya Administrator yang boleh mengubah data katekis lain
     * (Gate::before di AppServiceProvider meloloskan Administrator).
     * Katekis lain hanya boleh melihat.
     */
    public function manageKatekis(User $user, User $katekis): bool
    {
        return false;
    }

    /**
     * Hanya Administrator yang boleh mengubah data siswa
     * (Gate::before di AppServiceProvider meloloskan Administrator).
     */
    public function manageStudent(User $user, User $student): bool
    {
        return false;
    }
}
