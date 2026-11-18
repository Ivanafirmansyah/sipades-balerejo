<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageCompressor;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $beritas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('admin.berita.form', ['berita' => new Berita()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['judul']) . '-' . Str::random(5);
        $data['penulis'] = $request->user()->name;
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;
        $data['gambar'] = $this->upload($request);

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $data = $this->validated($request);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($berita->published_at ?? now()) : null;

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $this->upload($request);
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }
        $berita->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string'],
            'kategori' => ['required', 'in:berita,pengumuman'],
            'gambar' => ['nullable', 'image', 'max:10240'],
        ]);
    }

    private function upload(Request $request): ?string
    {
        if ($request->hasFile('gambar')) {
            return app(ImageCompressor::class)->store($request->file('gambar'), 'berita');
        }

        return null;
    }
}
