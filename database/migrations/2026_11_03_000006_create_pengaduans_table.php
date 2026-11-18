<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_pelapor')->nullable();
            $table->string('kategori');
            $table->text('isi');
            $table->string('lokasi')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->text('tanggapan')->nullable();
            $table->timestamp('tanggapan_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
