<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pengamanan transport & header HTTP (pelindungan data pribadi, UU No. 27/2022).
 *
 * Mode production (APP_ENV=production, yaitu saat di hosting):
 *  - semua permintaan http:// dialihkan ke https:// (data terkirim terenkripsi)
 *  - header HSTS: browser mengingat situs ini wajib HTTPS
 *
 * Semua mode:
 *  - header keamanan dasar (anti MIME-sniffing, anti clickjacking, kebijakan referrer)
 *  - halaman berisi data pribadi (admin, dashboard, akun, data isian) tidak boleh disimpan
 *    di cache browser, agar tidak terlihat lagi lewat tombol "Back" setelah logout di perangkat bersama.
 */
class KeamananHttp
{
    private const HALAMAN_PRIBADI = ['admin', 'admin/*', 'dashboard', 'dashboard/*', 'akun/*', 'formulir/*/isian/*'];

    public function handle(Request $request, Closure $next): Response
    {
        $production = app()->isProduction();

        if ($production && ! $request->secure()) {
            // 308 mempertahankan metode (POST tetap POST)
            return redirect()->secure($request->getRequestUri(), $request->isMethod('GET') || $request->isMethod('HEAD') ? 301 : 308);
        }

        $response = $next($request);

        $h = $response->headers;
        $h->set('X-Content-Type-Options', 'nosniff');
        $h->set('X-Frame-Options', 'SAMEORIGIN');
        $h->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $h->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self), payment=()');

        if ($production && $request->secure()) {
            $h->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if ($request->is(...self::HALAMAN_PRIBADI)) {
            $h->set('Cache-Control', 'no-store, private, max-age=0');
            $h->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
