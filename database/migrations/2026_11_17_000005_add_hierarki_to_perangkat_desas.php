<?php

use App\Models\PerangkatDesa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur organisasi berbentuk pohon:
 * - atasan_id : atasan langsung (Kepala Desa = akar pohon)
 * - urutan    : urutan tampil di antara bawahan satu atasan
 * - is_mitra  : lembaga mitra (mis. BPD) — ditampilkan di samping Kepala Desa, bukan bawahan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perangkat_desas', function (Blueprint $table) {
            $table->foreignId('atasan_id')->nullable()->after('jabatan')->constrained('perangkat_desas')->nullOnDelete();
            $table->unsignedSmallInteger('urutan')->default(0)->after('atasan_id');
            $table->boolean('is_mitra')->default(false)->after('urutan');
        });

        // Isi otomatis untuk data yang sudah ada, berdasarkan jabatan
        PerangkatDesa::all()->each(function (PerangkatDesa $p) {
            $p->forceFill(PerangkatDesa::tebakPosisi($p->jabatan, $p->id))->saveQuietly();
        });
    }

    public function down(): void
    {
        Schema::table('perangkat_desas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('atasan_id');
            $table->dropColumn(['urutan', 'is_mitra']);
        });
    }
};
