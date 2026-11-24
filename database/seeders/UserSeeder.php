<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $katekis = [
            ['name' => 'Romo Antonius', 'email' => 'antonius@gerejakalasan.org'],
            ['name' => 'Suster Maria',  'email' => 'maria@gerejakalasan.org'],
            ['name' => 'Bapak Yohanes', 'email' => 'yohanes@gerejakalasan.org'],
        ];

        foreach ($katekis as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'role'      => 'katekis',
                    'is_active' => true,
                    'password'  => Hash::make('katekis123'),
                ]
            );
        }

        $peserta = [
            ['name' => 'Andreas Santoso',  'email' => 'andreas@example.com'],
            ['name' => 'Bernadette Dewi',  'email' => 'bernadette@example.com'],
            ['name' => 'Christophorus Budi', 'email' => 'christophorus@example.com'],
            ['name' => 'Dominika Putri',   'email' => 'dominika@example.com'],
            ['name' => 'Emmanuel Wijaya',  'email' => 'emmanuel@example.com'],
        ];

        foreach ($peserta as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'role'      => 'peserta',
                    'is_active' => true,
                    'password'  => Hash::make('peserta123'),
                ]
            );
        }
    }
}
