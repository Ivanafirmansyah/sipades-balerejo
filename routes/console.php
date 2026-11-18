<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Buat / sinkronkan akun login (NIK) untuk semua data penduduk.
| Akun baru otomatis dibuat saat admin menambah penduduk; perintah ini
| untuk data lama atau data yang diimpor langsung ke database.
*/
Artisan::command('warga:buat-akun', function () {
    $baru = 0;
    $total = 0;
    \App\Models\Penduduk::with('user')->chunkById(200, function ($rows) use (&$baru, &$total) {
        foreach ($rows as $p) {
            $ada = (bool) $p->user;
            \App\Services\AkunWarga::sinkron($p);
            $total++;
            $baru += $ada ? 0 : 1;
        }
    });
    $tanpaTgl = \App\Models\Penduduk::whereNull('tanggal_lahir')->count();
    $this->info("Selesai: {$total} penduduk diperiksa, {$baru} akun baru dibuat.");
    if ($tanpaTgl) {
        $this->warn("{$tanpaTgl} penduduk belum punya tanggal lahir — mereka belum bisa masuk sampai tanggal lahir dilengkapi.");
    }
})->purpose('Buat akun login NIK untuk semua data penduduk');
