<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $query = Penduduk::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('no_kk', 'like', "%{$q}%");
            });
        }
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $penduduks = (clone $query)->with('user')->orderBy('rt')->orderBy('nama')->paginate(20)->withQueryString();

        $stats = [
            'total' => (clone $query)->count(),
            'laki' => (clone $query)->where('jenis_kelamin', 'L')->count(),
            'perempuan' => (clone $query)->where('jenis_kelamin', 'P')->count(),
            'kk' => (clone $query)->where('status', 'Kepala Keluarga')->count(),
        ];

        $rtList = Penduduk::select('rt')->distinct()->orderBy('rt')->pluck('rt');
        $rwList = Penduduk::select('rw')->distinct()->orderBy('rw')->pluck('rw');
        $perRt = Penduduk::selectRaw('rt, rw, count(*) as jumlah')
            ->groupBy('rt', 'rw')->orderBy('rt')->get();

        return view('admin.penduduk.index', compact('penduduks', 'stats', 'rtList', 'rwList', 'perRt'));
    }

    public function create()
    {
        return view('admin.penduduk.form', ['penduduk' => new Penduduk()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $penduduk = Penduduk::create($data); // akun login warga dibuat otomatis (PendudukObserver)

        $info = $penduduk->tanggal_lahir
            ? " Akun warga otomatis dibuat: login dengan NIK {$penduduk->nik}, kata sandi awal tanggal lahir ({$penduduk->tanggal_lahir->format('dmY')})."
            : ' Akun warga dibuat, tetapi belum bisa dipakai sampai tanggal lahir diisi.';

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan.' . $info);
    }

    public function edit(Penduduk $penduduk)
    {
        return view('admin.penduduk.form', compact('penduduk'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $data = $this->validated($request, $penduduk->id);
        $penduduk->update($data);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /** Kembalikan kata sandi warga ke tanggal lahir (DDMMYYYY); warga wajib menggantinya saat masuk. */
    public function resetSandi(Penduduk $penduduk)
    {
        if ($penduduk->user?->role === 'admin') {
            return back()->with('error', 'Akun ini adalah akun admin. Ubah kata sandinya lewat menu Manajemen Admin.');
        }
        if (! \App\Services\AkunWarga::resetSandi($penduduk)) {
            return back()->with('error', "Tanggal lahir {$penduduk->nama} belum diisi. Lengkapi dulu data penduduknya agar warga bisa masuk.");
        }

        return back()->with('success', "Kata sandi {$penduduk->nama} dikembalikan ke tanggal lahir ({$penduduk->tanggal_lahir->format('dmY')}). Warga akan diminta membuat sandi baru saat masuk.");
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();

        return back()->with('success', 'Data penduduk berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nik' => ['required', 'digits:16', Rule::unique('penduduks', 'nik')->ignore($ignoreId)],
            'no_kk' => ['nullable', 'digits:16'],
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
            'status' => ['required', 'in:Kepala Keluarga,Anggota'],
        ]);
    }
}
