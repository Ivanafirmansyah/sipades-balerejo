<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Login warga memakai NIK:
 * - users.nik              : NIK untuk login (unik), diisi otomatis dari data penduduk.
 * - users.email            : boleh kosong (akun warga otomatis tidak punya email).
 * - users.wajib_ganti_sandi: true selama warga masih memakai sandi awal (tanggal lahir).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('nik', 16)->nullable()->unique()->after('email');
            $table->boolean('wajib_ganti_sandi')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik']);
            $table->dropColumn(['nik', 'wajib_ganti_sandi']);
        });
    }
};
