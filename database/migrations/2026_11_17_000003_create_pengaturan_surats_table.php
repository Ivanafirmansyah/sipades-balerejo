<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur isi surat yang bisa diatur admin (tanpa mengubah kode):
 * kop surat, penandatangan, format nomor otomatis, kalimat pembuka/penutup,
 * serta paragraf isi khusus untuk setiap jenis surat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_surats', function (Blueprint $table) {
            $table->id();
            // Kop surat
            $table->string('kop_baris_1')->nullable();          // PEMERINTAH KABUPATEN ...
            $table->string('kop_baris_2')->nullable();          // KECAMATAN ...
            $table->string('kop_baris_3')->nullable();          // DESA ...
            $table->string('kop_alamat')->nullable();
            $table->boolean('tampilkan_logo')->default(true);
            // Penandatangan
            $table->string('tempat_surat')->nullable();         // tempat di atas tanda tangan
            $table->string('ttd_jabatan')->nullable();          // Kepala Desa / Lurah / a.n. ...
            $table->string('ttd_nama')->nullable();
            $table->string('ttd_nip')->nullable();
            // Nomor surat otomatis
            $table->string('format_nomor')->default('470/{nomor}/{kode}/{bulan_romawi}/{tahun}');
            $table->unsignedInteger('nomor_terakhir')->default(0);
            $table->unsignedSmallInteger('tahun_nomor')->nullable();
            // Kalimat
            $table->text('kalimat_pembuka')->nullable();
            $table->text('isi_bawaan')->nullable();             // dipakai bila jenis surat tidak punya isi khusus
            $table->text('kalimat_penutup')->nullable();
            $table->timestamps();
        });

        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->text('isi_surat')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_surats', fn (Blueprint $table) => $table->dropColumn('isi_surat'));
        Schema::dropIfExists('pengaturan_surats');
    }
};
