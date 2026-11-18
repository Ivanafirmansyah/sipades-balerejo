<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Formulir;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;

class SitusController extends Controller
{
    private function profilData(): ProfilDesa
    {
        return ProfilDesa::first() ?? new ProfilDesa([
            'nama_desa' => 'Desa Sejahtera',
            'kecamatan' => '-',
            'kabupaten' => '-',
            'provinsi' => '-',
        ]);
    }

    public function home()
    {
        $profil = $this->profilData();
        $beritas = Berita::where('is_published', true)->latest('published_at')->latest()->take(3)->get();
        $agendas = Agenda::whereDate('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get();
        $stats = [
            'penduduk' => Penduduk::count(),
            'kk' => Penduduk::where('status', 'Kepala Keluarga')->count(),
            'laki' => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Penduduk::where('jenis_kelamin', 'P')->count(),
            'rt' => Penduduk::distinct()->count('rt'),
            'rw' => Penduduk::distinct()->count('rw'),
        ];
        $kepalaDesa = PerangkatDesa::where('jabatan', 'Kepala Desa')->first();

        return view('home', compact('profil', 'beritas', 'agendas', 'stats', 'kepalaDesa'));
    }

    public function profil()
    {
        $profil = $this->profilData();
        return view('profil', compact('profil'));
    }

    public function struktur()
    {
        $profil = $this->profilData();
        $akar = PerangkatDesa::pohon();                                   // puncak struktur + seluruh bawahannya
        $mitra = PerangkatDesa::where('is_mitra', true)->orderBy('urutan')->orderBy('id')->get();
        $jumlah = PerangkatDesa::count();

        return view('struktur', compact('profil', 'akar', 'mitra', 'jumlah'));
    }

    public function peta()
    {
        $profil = $this->profilData();
        $lokasis = \App\Models\LokasiDesa::where('is_aktif', true)->orderByDesc('is_utama')->orderBy('kategori')->orderBy('nama')->get();
        $data = $lokasis->map->untukPeta()->values();
        $jumlah = $lokasis->countBy('kategori');
        $pusat = \App\Models\LokasiDesa::pusat();

        return view('peta', compact('profil', 'lokasis', 'data', 'jumlah', 'pusat'));
    }

    public function berita(Request $request)
    {
        $profil = $this->profilData();
        $kategori = $request->query('kategori');
        $query = Berita::where('is_published', true)->latest('published_at')->latest();
        if (in_array($kategori, ['berita', 'pengumuman'])) {
            $query->where('kategori', $kategori);
        }
        $beritas = $query->paginate(9)->withQueryString();
        return view('berita.index', compact('profil', 'beritas', 'kategori'));
    }

    public function beritaShow(string $slug)
    {
        $profil = $this->profilData();
        $berita = Berita::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $lainnya = Berita::where('is_published', true)->where('id', '!=', $berita->id)
            ->latest('published_at')->latest()->take(4)->get();
        return view('berita.show', compact('profil', 'berita', 'lainnya'));
    }

    public function agenda()
    {
        $profil = $this->profilData();
        $agendas = Agenda::orderBy('tanggal', 'desc')->paginate(9);
        return view('agenda.index', compact('profil', 'agendas'));
    }

    public function layanan()
    {
        $profil = $this->profilData();
        $jenisSurats = JenisSurat::where('is_aktif', true)->orderBy('nama')->get();
        $formulirs = Formulir::where('is_aktif', true)->latest()->get();
        return view('layanan', compact('profil', 'jenisSurats', 'formulirs'));
    }

    public function dataPenduduk(Request $request)
    {
        $profil = $this->profilData();
        $query = Penduduk::query();

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }
        // Halaman publik HANYA menampilkan statistik (angka agregat), tanpa nama/NIK/alamat warga
        // — pelindungan data pribadi (UU No. 27/2022). Daftar nama lengkap hanya di panel admin.
        $stats = [
            'total' => (clone $query)->count(),
            'laki' => (clone $query)->where('jenis_kelamin', 'L')->count(),
            'perempuan' => (clone $query)->where('jenis_kelamin', 'P')->count(),
            'kk' => (clone $query)->where('status', 'Kepala Keluarga')->count(),
        ];

        // Kelompok umur dihitung dari tanggal lahir (hanya kolom tanggal lahir yang dibaca)
        $kelompok = ['Balita' => 0, 'Anak' => 0, 'Remaja' => 0, 'Dewasa' => 0, 'Lansia' => 0, 'Tidak diketahui' => 0];
        (clone $query)->select('tanggal_lahir')->get()->each(function ($p) use (&$kelompok) {
            $kelompok[$p->kelompok_umur ?? 'Tidak diketahui']++;
        });
        if ($kelompok['Tidak diketahui'] === 0) {
            unset($kelompok['Tidak diketahui']);
        }

        $rtList = Penduduk::select('rt')->distinct()->orderBy('rt')->pluck('rt');
        $rwList = Penduduk::select('rw')->distinct()->orderBy('rw')->pluck('rw');
        $perRt = (clone $query)->selectRaw("rt, rw, count(*) as jumlah, sum(jenis_kelamin = 'L') as laki, sum(jenis_kelamin = 'P') as perempuan")
            ->groupBy('rt', 'rw')->orderBy('rw')->orderBy('rt')->get();

        // Data untuk grafik shadcn/ui (hanya angka agregat)
        $grafik = [
            'wilayah' => $perRt->map(fn ($r) => ['wilayah' => "RT {$r->rt}/RW {$r->rw}", 'laki' => (int) $r->laki, 'perempuan' => (int) $r->perempuan])->values(),
            'umur' => collect($kelompok)->map(fn ($n, $k) => ['kelompok' => $k, 'jumlah' => $n])->values(),
            'pekerjaan' => $this->peringkat($query, 'pekerjaan', 6),
            'agama' => $this->peringkat($query, 'agama', 6),
        ];

        return view('penduduk', compact('profil', 'stats', 'kelompok', 'rtList', 'rwList', 'perRt', 'grafik'));
    }

    /** Jumlah warga per nilai kolom (mis. pekerjaan), terbanyak dulu; sisanya digabung "Lainnya". */
    private function peringkat($query, string $kolom, int $batas): array
    {
        // $kolom hanya diisi dari kode ('pekerjaan'/'agama'), bukan dari input pengguna
        $ekspresi = "COALESCE(NULLIF(TRIM($kolom), ''), 'Tidak diisi')";
        $baris = (clone $query)->selectRaw("$ekspresi as nama, count(*) as jumlah")
            ->groupByRaw($ekspresi)->orderByDesc('jumlah')->get();
        $atas = $baris->take($batas)->map(fn ($r) => ['nama' => $r->nama, 'jumlah' => (int) $r->jumlah])->values()->all();
        $sisa = (int) $baris->slice($batas)->sum('jumlah');
        if ($sisa > 0) {
            $atas[] = ['nama' => 'Lainnya', 'jumlah' => $sisa];
        }

        return $atas;
    }

    public function kontak()
    {
        $profil = $this->profilData();
        return view('kontak', compact('profil'));
    }
}
