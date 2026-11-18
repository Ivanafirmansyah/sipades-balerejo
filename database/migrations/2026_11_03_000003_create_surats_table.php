<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('penduduk_id');
            $table->unsignedBigInteger('jenis_surat_id');
            $table->string('keperluan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->string('no_surat')->nullable();
            $table->string('file_path')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
