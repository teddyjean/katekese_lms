<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->string('nama_romo')->nullable()->after('description');
            $table->date('tanggal_sakramen')->nullable()->after('nama_romo');
        });

        Schema::table('batch_participants', function (Blueprint $table) {
            $table->boolean('lulus')->nullable()->after('rejection_note');
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['nama_romo', 'tanggal_sakramen']);
        });

        Schema::table('batch_participants', function (Blueprint $table) {
            $table->dropColumn('lulus');
        });
    }
};
