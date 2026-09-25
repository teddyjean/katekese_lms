<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.email');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrator',
                'email' => $email,
                'phone' => null,
                'role' => 'administrator',
                'is_active' => true,
                'password' => Hash::make(config('admin.password')),
            ]
        );
    }
}
