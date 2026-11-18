<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.agenda.index', compact('agendas'));
    }

    public function create()
    {
        return view('admin.agenda.form', ['agenda' => new Agenda()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['foto'] = $this->unggahFoto($request);

        Agenda::create($data);

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agenda.form', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $this->hapusFoto($agenda);
            $data['foto'] = $this->unggahFoto($request);
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusFoto($agenda);
            $data['foto'] = null;
        }

        $agenda->update($data);

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $this->hapusFoto($agenda);
        $agenda->delete();

        return back()->with('success', 'Agenda berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
            'jam' => ['nullable', 'date_format:H:i'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'foto' => ['nullable', 'image', 'max:10240'],
        ], [
            'foto.image' => 'File harus berupa gambar (JPG, PNG, WebP).',
            'foto.max' => 'Ukuran foto maksimal 10 MB.',
        ]);
    }

    /** Simpan foto kegiatan, dikompres otomatis (maks. 1600 px, WebP) agar ringan tapi tetap tajam. */
    private function unggahFoto(Request $request): ?string
    {
        return $request->hasFile('foto')
            ? app(ImageCompressor::class)->store($request->file('foto'), 'agenda', 1600)
            : null;
    }

    private function hapusFoto(Agenda $agenda): void
    {
        if ($agenda->foto) {
            Storage::disk('public')->delete($agenda->foto);
        }
    }
}
