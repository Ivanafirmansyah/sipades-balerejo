<?php

namespace App\Observers;

use App\Models\Penduduk;
use App\Services\AkunWarga;

/** Menjaga akun warga selalu sesuai data penduduk yang dimasukkan admin. */
class PendudukObserver
{
    public function created(Penduduk $penduduk): void
    {
        AkunWarga::sinkron($penduduk);
    }

    public function updated(Penduduk $penduduk): void
    {
        if (! $penduduk->wasChanged(['nik', 'nama', 'tanggal_lahir', 'user_id'])) {
            return;
        }

        $user = AkunWarga::sinkron($penduduk);

        // Tanggal lahir diperbaiki admin & warga belum pernah ganti sandi -> sandi awal ikut diperbarui
        if ($penduduk->wasChanged('tanggal_lahir') && $user->wajib_ganti_sandi && $user->role === 'user') {
            AkunWarga::resetSandi($penduduk);
        }
    }

    public function deleted(Penduduk $penduduk): void
    {
        // Data penduduk dihapus: akun warga tidak bisa lagi masuk dengan NIK.
        // Akun tetap disimpan agar riwayat surat & pengaduan tidak hilang.
        $user = $penduduk->user;
        if ($user && $user->role === 'user') {
            $user->forceFill(['nik' => null, 'penduduk_id' => null])->save();
        }
    }
}
