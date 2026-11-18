<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Pengaduan;
use App\Models\Penduduk;
use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        $stats = [
            'total' => Surat::where('user_id', $userId)->count(),
            'menunggu' => Surat::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'diproses' => Surat::where('user_id', $userId)->where('status', 'diproses')->count(),
            'selesai' => Surat::where('user_id', $userId)->where('status', 'selesai')->count(),
            'pengaduan' => Pengaduan::where('user_id', $userId)->count(),
        ];

        $surats = Surat::with('jenisSurat')->where('user_id', $userId)->latest()->take(5)->get();

        return view('user.dashboard', compact('stats', 'surats'));
    }

    public function suratCreate()
    {
        $jenisSurats = JenisSurat::where('is_aktif', true)->orderBy('nama')->get();
        $penduduk = auth()->user()->penduduk;

        return view('user.surat.create', compact('jenisSurats', 'penduduk'));
    }

    public function suratStore(Request $request)
    {
        $data = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'keperluan' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'nama' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'agama' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            'dusun' => ['nullable', 'string', 'max:100'],
        ]);

        $user = auth()->user();

        $penduduk = Penduduk::firstOrCreate(
            ['nik' => $data['nik']],
            [
                'nama' => $data['nama'],
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'] ?? null,
                'pekerjaan' => $data['pekerjaan'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'dusun' => $data['dusun'] ?? null,
                'status' => 'Anggota',
                'user_id' => $user->id,
            ]
        );

        if (is_null($penduduk->user_id)) {
            $penduduk->update(['user_id' => $user->id]);
        }

        $surat = Surat::create([
            'user_id' => $user->id,
            'penduduk_id' => $penduduk->id,
            'jenis_surat_id' => $data['jenis_surat_id'],
            'keperluan' => $data['keperluan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('user.surat.index')
            ->with('success', 'Pengajuan surat berhasil dikirim. Nomor pengajuan: #' . $surat->id);
    }

    public function suratIndex(Request $request)
    {
        $query = Surat::with(['jenisSurat', 'penduduk'])->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $surats = $query->latest()->paginate(10)->withQueryString();

        return view('user.surat.index', compact('surats'));
    }

    public function suratPdf(Surat $surat)
    {
        abort_unless($surat->user_id === auth()->id() && $surat->status === 'selesai', 403);

        $surat->load(['penduduk', 'jenisSurat', 'user']);
        $profil = \App\Models\ProfilDesa::first();

        $pdf = Pdf::loadView('pdf.surat', compact('surat', 'profil'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('surat-' . $surat->id . '.pdf');
    }

    public function pengaduanCreate()
    {
        return view('user.pengaduan.create');
    }

    public function pengaduanStore(Request $request)
    {
        $data = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'isi' => ['required', 'string'],
            'lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        Pengaduan::create([
            'user_id' => auth()->id(),
            'nama_pelapor' => auth()->user()->name,
            'kategori' => $data['kategori'],
            'isi' => $data['isi'],
            'lokasi' => $data['lokasi'] ?? null,
            'status' => 'baru',
        ]);

        return redirect()->route('user.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function pengaduanIndex()
    {
        $pengaduans = Pengaduan::where('user_id', auth()->id())->latest()->paginate(10);

        return view('user.pengaduan.index', compact('pengaduans'));
    }
}
