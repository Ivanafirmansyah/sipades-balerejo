<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Nama hari & bulan berbahasa Indonesia (mis. "17 Agustus 1990") di surat, PDF, dan halaman
        \Illuminate\Support\Carbon::setLocale('id');

        // Di hosting (production) semua tautan & aset dibuat dengan https://
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
