<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Peta Fasilitas Desa: titik lokasi fasilitas UMUM (balai desa, masjid, sekolah, posyandu, dst.)
 * yang dikelola admin. Bukan untuk rumah pribadi warga (pelindungan data pribadi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori', 30);
            $table->text('keterangan')->nullable();
            $table->string('alamat')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('foto')->nullable();
            $table->boolean('is_utama')->default(false);   // titik utama (kantor/balai desa) = pusat peta
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->index(['kategori', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_desas');
    }
};
