<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\ProfilDesa;
use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with(['user', 'penduduk', 'jenisSurat']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_surat', 'like', "%{$q}%")
                    ->orWhereHas('penduduk', fn ($p) => $p->where('nama', 'like', "%{$q}%")->orWhere('nik', 'like', "%{$q}%"))
                    ->orWhereHas('jenisSurat', fn ($j) => $j->where('nama', 'like', "%{$q}%"));
            });
        }

        $surats = $query->latest()->paginate(15)->withQueryString();
        $stats = [
            'menunggu' => Surat::where('status', 'menunggu')->count(),
            'diproses' => Surat::where('status', 'diproses')->count(),
            'selesai' => Surat::where('status', 'selesai')->count(),
            'ditolak' => Surat::where('status', 'ditolak')->count(),
        ];

        return view('admin.surat.index', compact('surats', 'stats'));
    }

    public function update(Request $request, Surat $surat)
    {
        $data = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai,ditolak'],
            'no_surat' => ['nullable', 'string', 'max:100'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $data['diproses_at'] = in_array($data['status'], ['diproses', 'selesai'])
            ? ($surat->diproses_at ?? now())
            : null;

        $data['selesai_at'] = $data['status'] === 'selesai'
            ? ($surat->selesai_at ?? now())
            : null;

        // Nomor surat otomatis sesuai format di "Pengaturan Surat" bila admin tidak mengisinya
        if ($data['status'] === 'selesai' && blank($data['no_surat'] ?? null) && blank($surat->no_surat)) {
            $data['no_surat'] = \App\Models\PengaturanSurat::aktif()->nomorBerikutnya($surat->jenisSurat, $data['selesai_at']);
        }

        $surat->update($data);

        return back()->with('success', 'Status surat #' . $surat->id . ' berhasil diperbarui.');
    }

    public function pdf(Surat $surat)
    {
        $surat->load(['penduduk', 'jenisSurat', 'user']);
        $profil = ProfilDesa::first();

        $pdf = Pdf::loadView('pdf.surat', compact('surat', 'profil'))->setPaper('a4', 'portrait');

        return $pdf->download('surat-' . $surat->id . '.pdf');
    }

    public function exportPdf()
    {
        $surats = Surat::with(['penduduk', 'jenisSurat'])->latest()->get();
        $profil = ProfilDesa::first();
        $pdf = Pdf::loadView('pdf.surat_laporan', compact('surats', 'profil'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-surat-' . now()->format('Ymd') . '.pdf');
    }

    public function jenisIndex()
    {
        $jenisSurats = JenisSurat::withCount('surats')->orderBy('nama')->get();

        return view('admin.surat.jenis', compact('jenisSurats'));
    }

    public function jenisStore(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
            'is_aktif' => ['nullable'],
        ]);

        $data['is_aktif'] = $request->boolean('is_aktif');
        JenisSurat::create($data);

        return back()->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function jenisUpdate(Request $request, JenisSurat $jenisSurat)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $data['is_aktif'] = $request->boolean('is_aktif');
        $jenisSurat->update($data);

        return back()->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function jenisDestroy(JenisSurat $jenisSurat)
    {
        if ($jenisSurat->surats()->exists()) {
            return back()->with('error', 'Jenis surat tidak dapat dihapus karena sudah dipakai pada pengajuan.');
        }

        $jenisSurat->delete();

        return back()->with('success', 'Jenis surat berhasil dihapus.');
    }
}
