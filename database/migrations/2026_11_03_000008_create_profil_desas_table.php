<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('logo')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('alamat_kantor')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('peta_embed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_desas');
    }
};
