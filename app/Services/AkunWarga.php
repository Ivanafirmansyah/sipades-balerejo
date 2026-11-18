<?php

namespace App\Services;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Akun warga otomatis dari data penduduk.
 *
 * - Setiap penduduk yang dimasukkan admin otomatis punya akun (login pakai NIK).
 * - Sandi awal = tanggal lahir format DDMMYYYY, wajib diganti saat pertama masuk.
 * - Penduduk tanpa tanggal lahir tetap dibuatkan akun, tetapi belum bisa masuk
 *   sampai admin melengkapi tanggal lahirnya.
 */
class AkunWarga
{
    /** Sandi awal warga: tanggal lahir DDMMYYYY, atau null bila tanggal lahir kosong. */
    public static function sandiAwal(Penduduk $penduduk): ?string
    {
        return $penduduk->tanggal_lahir?->format('dmY');
    }

    /** Buat atau perbarui akun milik penduduk ini. */
    public static function sinkron(Penduduk $penduduk): User
    {
        $user = $penduduk->user
            ?? User::where('penduduk_id', $penduduk->id)->first()
            ?? User::where('nik', $penduduk->nik)->first();

        if (! $user) {
            $sandi = self::sandiAwal($penduduk);
            $user = User::create([
                'name' => $penduduk->nama,
                'nik' => $penduduk->nik,
                'role' => 'user',
                'penduduk_id' => $penduduk->id,
                // tanpa tanggal lahir: sandi acak (belum bisa masuk sampai admin melengkapi data)
                'password' => Hash::make($sandi ?? Str::random(40)),
                'wajib_ganti_sandi' => true,
            ]);
        } else {
            $ubah = ['penduduk_id' => $penduduk->id];
            // NIK hanya dipasang bila belum dipakai akun lain
            if ($user->nik !== $penduduk->nik && ! User::where('nik', $penduduk->nik)->whereKeyNot($user->id)->exists()) {
                $ubah['nik'] = $penduduk->nik;
            }
            if ($user->role === 'user') {
                $ubah['name'] = $penduduk->nama;
            }
            $user->forceFill($ubah)->save();
        }

        if ($penduduk->user_id !== $user->id) {
            $penduduk->user_id = $user->id;
            $penduduk->saveQuietly(); // tanpa memicu observer lagi
        }

        return $user;
    }

    /** Kembalikan sandi warga ke tanggal lahir dan wajibkan ganti saat masuk. */
    public static function resetSandi(Penduduk $penduduk): bool
    {
        $sandi = self::sandiAwal($penduduk);
        if (! $sandi) {
            return false;
        }
        $user = self::sinkron($penduduk);
        $user->forceFill(['password' => Hash::make($sandi), 'wajib_ganti_sandi' => true])->save();
        self::bukaKunci($penduduk->nik); // reset oleh admin sekaligus membuka akun yang terkunci

        return true;
    }

    /* ---------------- Batas percobaan masuk (per NIK / email) ----------------
     | 3 kali salah  -> dikunci 15 menit.
     | 10 kali salah dalam 24 jam -> dikunci 24 jam (warga perlu menghubungi kantor desa;
     |                               admin bisa membuka dengan "Reset kata sandi").
     | Dengan begini menebak tanggal lahir hanya bisa ±10 kali sehari -> tidak praktis.
     */
    public const MAKS_PERCOBAAN = 3;
    public const LAMA_KUNCI = 15 * 60;
    public const MAKS_HARIAN = 10;

    private static function kunci(string $login): array
    {
        $id = sha1(mb_strtolower(trim($login)));

        return ['login:' . $id, 'login-harian:' . $id];
    }

    /** Pesan bila sedang dikunci, atau null bila boleh mencoba. */
    public static function cekKunci(string $login): ?string
    {
        [$singkat, $harian] = self::kunci($login);

        if (RateLimiter::tooManyAttempts($harian, self::MAKS_HARIAN)) {
            $jam = (int) ceil(RateLimiter::availableIn($harian) / 3600);

            return "Akun ini dikunci sementara ({$jam} jam lagi) karena terlalu banyak percobaan masuk yang salah. "
                . 'Jika ini akun Anda, hubungi kantor desa untuk membukanya.';
        }
        if (RateLimiter::tooManyAttempts($singkat, self::MAKS_PERCOBAAN)) {
            $menit = max(1, (int) ceil(RateLimiter::availableIn($singkat) / 60));

            return "Terlalu banyak percobaan yang salah. Silakan coba lagi dalam {$menit} menit.";
        }

        return null;
    }

    /** Catat satu percobaan gagal; kembalikan keterangan sisa percobaan untuk ditambahkan ke pesan. */
    public static function catatGagal(string $login): string
    {
        [$singkat, $harian] = self::kunci($login);
        RateLimiter::hit($singkat, self::LAMA_KUNCI);
        RateLimiter::hit($harian, 24 * 3600);

        $sisa = RateLimiter::remaining($singkat, self::MAKS_PERCOBAAN);

        return $sisa > 0
            ? " Sisa {$sisa} kali percobaan."
            : ' Akun dikunci ' . (self::LAMA_KUNCI / 60) . ' menit.';
    }

    /** Hapus hitungan gagal (setelah berhasil masuk atau direset admin). */
    public static function bukaKunci(string $login): void
    {
        foreach (self::kunci($login) as $k) {
            RateLimiter::clear($k);
        }
    }

    /** Status akun untuk ditampilkan ke admin. */
    public static function status(Penduduk $penduduk): array
    {
        $user = $penduduk->user;
        if (! $user) {
            return ['label' => 'Belum ada', 'warna' => 'secondary', 'ikon' => 'bi-person-x'];
        }
        if ($user->role === 'admin') {
            return ['label' => 'Admin', 'warna' => 'info', 'ikon' => 'bi-shield-lock'];
        }
        if (self::cekKunci($penduduk->nik)) {
            return ['label' => 'Terkunci', 'warna' => 'danger', 'ikon' => 'bi-lock-fill'];
        }
        if (! $user->wajib_ganti_sandi) {
            return ['label' => 'Aktif', 'warna' => 'success', 'ikon' => 'bi-person-check'];
        }
        if (! $penduduk->tanggal_lahir) {
            return ['label' => 'Tgl lahir kosong', 'warna' => 'danger', 'ikon' => 'bi-exclamation-triangle'];
        }

        return ['label' => 'Belum masuk', 'warna' => 'warning', 'ikon' => 'bi-hourglass-split'];
    }
}
