<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Pengaduan;
use App\Models\Penduduk;
use App\Models\Surat;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'penduduk' => Penduduk::count(),
            'kk' => Penduduk::where('status', 'Kepala Keluarga')->count(),
            'surat_menunggu' => Surat::where('status', 'menunggu')->count(),
            'surat_selesai' => Surat::where('status', 'selesai')->count(),
            'pengaduan_baru' => Pengaduan::where('status', 'baru')->count(),
            'berita' => Berita::count(),
        ];

        $surats = Surat::with(['penduduk', 'jenisSurat'])->latest()->take(5)->get();
        $pengaduans = Pengaduan::latest()->take(5)->get();
        $agendas = Agenda::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(5)->get();
        $perRt = Penduduk::selectRaw('rt, rw, count(*) as jumlah')->groupBy('rt', 'rw')->orderBy('rt')->get();

        // Data grafik shadcn/ui
        $kelompok = ['Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0];
        Penduduk::select('tanggal_lahir')->get()->each(function ($p) use (&$kelompok) {
            if ($p->kelompok_umur) {
                $kelompok[$p->kelompok_umur]++;
            }
        });
        $grafik = [
            'wilayah' => Penduduk::selectRaw("rt, rw, sum(jenis_kelamin = 'L') as laki, sum(jenis_kelamin = 'P') as perempuan")
                ->groupBy('rt', 'rw')->orderBy('rw')->orderBy('rt')->get()
                ->map(fn ($r) => ['wilayah' => "RT {$r->rt}/RW {$r->rw}", 'laki' => (int) $r->laki, 'perempuan' => (int) $r->perempuan])->values(),
            'laki' => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Penduduk::where('jenis_kelamin', 'P')->count(),
            'umur' => collect($kelompok)->map(fn ($n, $k) => ['kelompok' => $k, 'jumlah' => $n])->values(),
            'surat' => collect(['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'])
                ->map(fn ($label, $status) => ['nama' => $label, 'jumlah' => Surat::where('status', $status)->count()])->values(),
        ];

        return view('admin.dashboard', compact('stats', 'surats', 'pengaduans', 'agendas', 'perRt', 'grafik'));
    }
}
