<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['name' => 'Calon Baptis',          'description' => 'Katekese bagi calon penerima Sakramen Baptis', 'status' => 'active', 'order' => 1],
            ['name' => 'Calon Komuni Pertama',   'description' => 'Katekese bagi calon penerima Komuni Pertama',  'status' => 'active', 'order' => 2],
            ['name' => 'Calon Krisma',           'description' => 'Katekese bagi calon penerima Sakramen Krisma', 'status' => 'active', 'order' => 3],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(['name' => $program['name']], $program);
        }
    }
}
