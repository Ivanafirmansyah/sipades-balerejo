<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Koreksi isian berversi (optimistic concurrency control):
 * - formulir_jawabans.versi        : naik setiap kali isian diubah (oleh warga atau status oleh admin).
 * - formulir_jawabans.kunci_edit   : hash kunci rahasia yang dibuat di perangkat pengirim,
 *                                     agar pengirim tanpa login hanya bisa mengoreksi dari perangkatnya.
 * - formulir_jawaban_riwayats      : salinan data sebelum setiap perubahan (audit trail), tidak ada data yang dibuang.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formulir_jawabans', function (Blueprint $table) {
            $table->unsignedInteger('versi')->default(1)->after('data');
            $table->string('kunci_edit', 64)->nullable()->after('client_uuid');
            $table->unsignedInteger('diedit_kali')->default(0)->after('versi');
            $table->timestamp('terakhir_diedit_at')->nullable()->after('diisi_pada');
        });

        Schema::create('formulir_jawaban_riwayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulir_jawaban_id')->constrained('formulir_jawabans')->cascadeOnDelete();
            $table->unsignedInteger('versi');                    // versi data yang disimpan di baris ini (sebelum diubah)
            $table->json('data');                                // salinan data versi tersebut
            $table->uuid('op_uuid')->nullable()->unique();       // id operasi koreksi, mencegah koreksi tercatat dua kali
            $table->foreignId('diubah_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('dikirim_offline')->default(false);
            $table->timestamp('diubah_pada')->nullable();        // waktu koreksi dibuat di perangkat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulir_jawaban_riwayats');
        Schema::table('formulir_jawabans', function (Blueprint $table) {
            $table->dropColumn(['versi', 'kunci_edit', 'diedit_kali', 'terakhir_diedit_at']);
        });
    }
};
