<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gerejakalasan.org'],
            [
                'name' => 'Administrator',
                'email' => 'admin@gerejakalasan.org',
                'phone' => null,
                'role' => 'katekis',
                'is_active' => true,
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
