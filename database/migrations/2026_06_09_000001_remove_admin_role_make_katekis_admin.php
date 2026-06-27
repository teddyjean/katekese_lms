<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'admin')->update(['role' => 'katekis']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('katekis', 'peserta') NOT NULL DEFAULT 'peserta'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'katekis', 'peserta') NOT NULL DEFAULT 'peserta'");
    }
};
