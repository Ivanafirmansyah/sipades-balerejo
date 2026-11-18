<?php

namespace App\Http\Controllers;

use App\Models\ProfilDesa;

class PwaController extends Controller
{
    /** Manifest aplikasi (nama desa diambil dari profil). */
    public function manifest()
    {
        $profil = ProfilDesa::first();
        $nama = $profil->nama_desa ?? 'SIPADES';

        return response()->json([
            'name' => $nama . ' — Layanan Desa Digital',
            'short_name' => \Illuminate\Support\Str::limit($nama, 12, ''),
            'description' => 'Layanan surat, formulir, dan informasi ' . $nama . '. Formulir tetap bisa diisi tanpa sinyal.',
            'lang' => 'id',
            'start_url' => '/?sumber=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#0f3a7a',
            'theme_color' => '#0f3a7a',
            'icons' => [
                ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => '/icons/maskable-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'shortcuts' => [
                ['name' => 'Formulir Online', 'url' => '/formulir', 'icons' => [['src' => '/icons/icon-192.png', 'sizes' => '192x192']]],
                ['name' => 'Layanan Surat', 'url' => '/layanan', 'icons' => [['src' => '/icons/icon-192.png', 'sizes' => '192x192']]],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json']);
    }

    public function offline()
    {
        $profil = ProfilDesa::first();

        return view('offline', compact('profil'));
    }
}
