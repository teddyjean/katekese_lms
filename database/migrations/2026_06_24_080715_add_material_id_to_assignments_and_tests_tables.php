<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->after('batch_id')
                ->constrained('materials')->nullOnDelete();
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->after('batch_id')
                ->constrained('materials')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('material_id');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('material_id');
        });
    }
};
