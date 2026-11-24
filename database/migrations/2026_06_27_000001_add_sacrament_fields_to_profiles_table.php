<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('profiles', 'nama_ibu')) {
                $table->string('nama_ibu')->nullable()->after('nama_ayah');
            }
            if (! Schema::hasColumn('profiles', 'gereja_baptis')) {
                $table->string('gereja_baptis')->nullable()->after('nama_ibu');
            }
            if (! Schema::hasColumn('profiles', 'nomor_buku_baptis')) {
                $table->string('nomor_buku_baptis')->nullable()->after('gereja_baptis');
            }
            if (! Schema::hasColumn('profiles', 'gereja_komuni_pertama')) {
                $table->string('gereja_komuni_pertama')->nullable()->after('nomor_buku_baptis');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah',
                'nama_ibu',
                'gereja_baptis',
                'nomor_buku_baptis',
                'gereja_komuni_pertama',
            ]);
        });
    }
};
