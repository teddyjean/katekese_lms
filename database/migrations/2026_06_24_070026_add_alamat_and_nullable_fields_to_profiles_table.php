<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('nama_baptis');
        });

        // Sekolah/kelas/tanggal_lahir only apply to siswa; katekis profiles don't have them.
        DB::statement('ALTER TABLE profiles MODIFY COLUMN sekolah VARCHAR(255) NULL');
        DB::statement('ALTER TABLE profiles MODIFY COLUMN kelas VARCHAR(255) NULL');
        DB::statement('ALTER TABLE profiles MODIFY COLUMN tanggal_lahir DATE NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE profiles MODIFY COLUMN sekolah VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE profiles MODIFY COLUMN kelas VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE profiles MODIFY COLUMN tanggal_lahir DATE NOT NULL');

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
