<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
        // Kiriman antrian offline bisa tiba lama setelah halaman dibuka (token CSRF sudah kedaluwarsa).
        // Endpoint ini idempoten & dibatasi throttle; formulir yang wajib login tetap memakai sesi.
        $middleware->validateCsrfTokens(except: ['formulir/*/kirim', 'formulir/*/ubah']);
        // Warga yang masih memakai sandi awal (tanggal lahir) wajib menggantinya dulu
        $middleware->web(append: [\App\Http\Middleware\WajibGantiSandi::class]);
        // HTTPS paksa (production), HSTS, header keamanan, larangan cache halaman berisi data pribadi.
        // "append" (bukan prepend) agar berjalan SETELAH TrustProxies membaca header proxy hosting;
        // jika tidak, situs di belakang Cloudflare/proxy akan mengalihkan ke https tanpa henti.
        $middleware->append(\App\Http\Middleware\KeamananHttp::class);
        // Di hosting yang memakai proxy/CDN (mis. Cloudflare) isi TRUSTED_PROXIES=* agar HTTPS terdeteksi benar
        if ($proxy = env('TRUSTED_PROXIES')) {
            $middleware->trustProxies(at: $proxy === '*' ? '*' : explode(',', $proxy));
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
