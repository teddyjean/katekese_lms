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
        Schema::table('programs', function (Blueprint $table) {
            $table->unsignedTinyInteger('order')->nullable()->after('name');
        });

        $sequence = [
            'Calon Baptis' => 1,
            'Calon Komuni Pertama' => 2,
            'Calon Krisma' => 3,
        ];

        foreach ($sequence as $name => $order) {
            DB::table('programs')->where('name', $name)->update(['order' => $order]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
