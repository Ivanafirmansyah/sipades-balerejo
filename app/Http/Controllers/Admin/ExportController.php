<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function penduduk(Request $request)
    {
        $query = Penduduk::query();

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $penduduks = $query->orderBy('rt')->orderBy('nama')->get();
        $profil = ProfilDesa::first();
        $filter = [
            'rt' => $request->rt,
            'rw' => $request->rw,
        ];

        $pdf = Pdf::loadView('pdf.penduduk', compact('penduduks', 'profil', 'filter'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penduduk-' . now()->format('Ymd') . '.pdf');
    }

    public function pengaduan(Request $request)
    {
        $query = Pengaduan::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengaduans = $query->latest()->get();
        $profil = ProfilDesa::first();

        $pdf = Pdf::loadView('pdf.pengaduan', compact('pengaduans', 'profil'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengaduan-' . now()->format('Ymd') . '.pdf');
    }
}
