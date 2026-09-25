<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_baptis')->nullable();
            $table->string('sekolah')->nullable();
            $table->string('kelas')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('wilayah');
            $table->string('lingkungan');
            $table->timestamps();
        });

        // SQLite doesn't support ALTER COLUMN, drop and recreate
        Schema::drop('batch_participants');
        Schema::create('batch_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_note')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');

        Schema::drop('batch_participants');
        Schema::create('batch_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['batch_id', 'user_id']);
        });
    }
};
