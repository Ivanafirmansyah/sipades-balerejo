<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PerangkatController extends Controller
{
    public function index()
    {
        $perangkats = PerangkatDesa::with('atasan')->orderBy('is_mitra')->orderByRaw('atasan_id IS NOT NULL')->orderBy('urutan')->orderBy('id')->get();

        return view('admin.perangkat.index', compact('perangkats'));
    }

    public function create()
    {
        return view('admin.perangkat.form', [
            'perangkat' => new PerangkatDesa(),
            'pilihanAtasan' => PerangkatDesa::where('is_mitra', false)->orderBy('urutan')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('foto')) {
            $data['foto'] = app(ImageCompressor::class)->store($request->file('foto'), 'perangkat', 1080);
        }
        PerangkatDesa::create($data);

        return redirect()->route('admin.perangkat.index')->with('success', 'Perangkat desa berhasil ditambahkan ke struktur organisasi.');
    }

    public function edit(PerangkatDesa $perangkat)
    {
        // atasan tidak boleh dirinya sendiri atau bawahannya (mencegah struktur melingkar)
        $terlarang = $perangkat->idKeturunan();

        return view('admin.perangkat.form', [
            'perangkat' => $perangkat,
            'pilihanAtasan' => PerangkatDesa::where('is_mitra', false)->whereNotIn('id', $terlarang)->orderBy('urutan')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, PerangkatDesa $perangkat)
    {
        $data = $this->validated($request, $perangkat);
        if ($request->hasFile('foto')) {
            if ($perangkat->foto) {
                Storage::disk('public')->delete($perangkat->foto);
            }
            $data['foto'] = app(ImageCompressor::class)->store($request->file('foto'), 'perangkat', 1080);
        }
        $perangkat->update($data);

        return redirect()->route('admin.perangkat.index')->with('success', 'Perangkat desa berhasil diperbarui.');
    }

    public function destroy(PerangkatDesa $perangkat)
    {
        // bawahan dari perangkat yang dihapus dipindah ke atasannya, agar pohon tidak terputus
        PerangkatDesa::where('atasan_id', $perangkat->id)->update(['atasan_id' => $perangkat->atasan_id]);
        if ($perangkat->foto) {
            Storage::disk('public')->delete($perangkat->foto);
        }
        $perangkat->delete();

        return back()->with('success', 'Perangkat desa berhasil dihapus. Bawahannya dipindah ke atasan berikutnya.');
    }

    private function validated(Request $request, ?PerangkatDesa $perangkat = null): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:100'],
            'atasan' => ['nullable', 'string'],          // "otomatis", "akar", atau ID atasan
            'urutan' => ['nullable', 'integer', 'min:0', 'max:999'],
            'keterangan' => ['nullable', 'string', 'max:200'],
            'foto' => ['nullable', 'image', 'max:10240'],
        ]);
        $isMitra = $request->boolean('is_mitra');
        $pilihan = $data['atasan'] ?? 'otomatis';
        unset($data['atasan'], $data['foto']);

        if ($pilihan === 'otomatis') {
            $tebak = PerangkatDesa::tebakPosisi($data['jabatan'], $perangkat?->id);
            $data['atasan_id'] = $isMitra ? null : $tebak['atasan_id'];
            $data['is_mitra'] = $isMitra || $tebak['is_mitra'];
            $data['urutan'] = $data['urutan'] ?? $tebak['urutan'];
        } else {
            $atasanId = $pilihan === 'akar' ? null : (int) $pilihan;
            if ($atasanId) {
                $atasan = PerangkatDesa::find($atasanId);
                if (! $atasan || $atasan->is_mitra || ($perangkat && in_array($atasanId, $perangkat->idKeturunan(), true))) {
                    throw ValidationException::withMessages(['atasan' => 'Atasan tidak valid (tidak boleh diri sendiri, bawahannya, atau lembaga mitra).']);
                }
            }
            $data['atasan_id'] = $isMitra ? null : $atasanId;
            $data['is_mitra'] = $isMitra;
            $data['urutan'] = $data['urutan'] ?? 0;
        }

        return $data;
    }
}
