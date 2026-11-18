<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Warga yang masih memakai sandi awal (tanggal lahir) wajib membuat sandi baru
 * sebelum memakai fitur lain.
 */
class WajibGantiSandi
{
    /** Rute yang tetap boleh dibuka sebelum sandi diganti. */
    private const BEBAS = [
        'akun.sandi', 'akun.sandi.update', 'logout',
        'pwa.manifest', 'pwa.offline',
        // kiriman antrian offline: jangan dialihkan, redirect akan dikira "terkirim"
        'formulir.kirim', 'formulir.ubah', 'formulir.isian',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->wajib_ganti_sandi || $request->routeIs(...self::BEBAS)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => false, 'pesan' => 'Silakan buat kata sandi baru terlebih dahulu.'], 403);
        }

        return redirect()->route('akun.sandi');
    }
}
