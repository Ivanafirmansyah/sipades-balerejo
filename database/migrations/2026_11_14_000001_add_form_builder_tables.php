<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dynamic Form Builder:
 * - formulirs   : ditambah kolom skema JSON & pengaturan formulir dinamis.
 * - formulir_jawabans : menyimpan isian warga (data JSON), termasuk yang dikirim offline.
 * - surats.data : isian tambahan bila pengajuan surat berasal dari formulir dinamis.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formulirs', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('judul');
            $table->string('tipe', 20)->default('tautan')->after('slug');          // tautan | dinamis
            $table->string('kategori', 20)->default('pendataan')->after('tipe');   // pendataan | surat
            $table->foreignId('jenis_surat_id')->nullable()->after('kategori')->constrained('jenis_surats')->nullOnDelete();
            $table->json('schema')->nullable()->after('link');
            $table->boolean('butuh_login')->default(false)->after('schema');
            $table->string('pesan_sukses')->nullable()->after('butuh_login');
        });

        Schema::create('formulir_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulir_id')->constrained('formulirs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('surat_id')->nullable()->constrained('surats')->nullOnDelete();
            $table->uuid('client_uuid')->nullable()->unique();   // mencegah data ganda saat sinkronisasi ulang
            $table->json('data');
            $table->string('status', 20)->default('baru');      // baru | diproses | selesai
            $table->boolean('dikirim_offline')->default(false);
            $table->timestamp('diisi_pada')->nullable();         // waktu diisi di perangkat
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->json('data')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('surats', fn (Blueprint $table) => $table->dropColumn('data'));
        Schema::dropIfExists('formulir_jawabans');
        Schema::table('formulirs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jenis_surat_id');
            $table->dropColumn(['slug', 'tipe', 'kategori', 'schema', 'butuh_login', 'pesan_sukses']);
        });
    }
};
