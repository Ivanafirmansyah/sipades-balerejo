<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiDesa;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/** Admin mengelola titik fasilitas umum pada Peta Desa. */
class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $lokasis = LokasiDesa::when($request->kategori, fn ($q, $k) => $q->where('kategori', $k))
            ->orderByDesc('is_utama')->orderBy('kategori')->orderBy('nama')->get();

        return view('admin.lokasi.index', [
            'lokasis' => $lokasis,
            'semua' => LokasiDesa::all()->map->untukPeta()->values(),
            'jumlah' => LokasiDesa::selectRaw('kategori, count(*) as n')->groupBy('kategori')->pluck('n', 'kategori'),
            'pusat' => LokasiDesa::pusat(),
        ]);
    }

    public function create()
    {
        return view('admin.lokasi.form', [
            'lokasi' => new LokasiDesa(['kategori' => 'ibadah', 'is_aktif' => true, 'is_utama' => LokasiDesa::count() === 0]),
            'semua' => LokasiDesa::all()->map->untukPeta()->values(),
            'pusat' => LokasiDesa::pusat(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['foto'] = $request->hasFile('foto') ? $this->unggah($request) : null;

        DB::transaction(function () use ($data) {
            $lokasi = LokasiDesa::create($data);
            $this->satuUtama($lokasi);
        });

        return redirect()->route('admin.lokasi.index')->with('success', "Lokasi \"{$data['nama']}\" ditambahkan ke peta.");
    }

    public function edit(LokasiDesa $lokasi)
    {
        return view('admin.lokasi.form', [
            'lokasi' => $lokasi,
            'semua' => LokasiDesa::whereKeyNot($lokasi->id)->get()->map->untukPeta()->values(),
            'pusat' => [$lokasi->lat, $lokasi->lng],
        ]);
    }

    public function update(Request $request, LokasiDesa $lokasi)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $this->hapusFoto($lokasi);
            $data['foto'] = $this->unggah($request);
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusFoto($lokasi);
            $data['foto'] = null;
        }

        DB::transaction(function () use ($lokasi, $data) {
            $lokasi->update($data);
            $this->satuUtama($lokasi);
        });

        return redirect()->route('admin.lokasi.index')->with('success', "Lokasi \"{$lokasi->nama}\" diperbarui.");
    }

    public function destroy(LokasiDesa $lokasi)
    {
        $this->hapusFoto($lokasi);
        $lokasi->delete();

        return back()->with('success', "Lokasi \"{$lokasi->nama}\" dihapus dari peta.");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(array_keys(LokasiDesa::KATEGORI))],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'foto' => ['nullable', 'image', 'max:10240'],
        ], [
            'lat.required' => 'Tentukan titik lokasi: klik di peta atau isi koordinat.',
            'lng.required' => 'Tentukan titik lokasi: klik di peta atau isi koordinat.',
            'foto.image' => 'File harus berupa gambar (JPG, PNG, WebP).',
        ]);
        $data['is_utama'] = $request->boolean('is_utama');
        $data['is_aktif'] = $request->boolean('is_aktif');
        unset($data['foto']);

        return $data;
    }

    /** Hanya boleh ada satu titik utama (pusat peta). */
    private function satuUtama(LokasiDesa $lokasi): void
    {
        if ($lokasi->is_utama) {
            LokasiDesa::whereKeyNot($lokasi->id)->where('is_utama', true)->update(['is_utama' => false]);
        }
    }

    private function unggah(Request $request): string
    {
        return app(ImageCompressor::class)->store($request->file('foto'), 'lokasi', 1280);
    }

    private function hapusFoto(LokasiDesa $lokasi): void
    {
        if ($lokasi->foto) {
            Storage::disk('public')->delete($lokasi->foto);
        }
    }
}
