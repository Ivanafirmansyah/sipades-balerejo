<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengaturanSurat;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\Surat;
use App\Support\SuratTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/** Admin mengatur struktur isi surat: kop, penandatangan, nomor, kalimat, dan isi per jenis surat. */
class PengaturanSuratController extends Controller
{
    public function edit()
    {
        return view('admin.surat.pengaturan', [
            'ps' => PengaturanSurat::aktif(),
            'jenisSurats' => JenisSurat::orderBy('nama')->get(),
            'kataKunci' => SuratTemplate::KATA_KUNCI,
        ]);
    }

    public function update(Request $request)
    {
        $ps = PengaturanSurat::aktif();
        $data = $this->validated($request);

        $ps->update($data);
        foreach ((array) $request->input('isi', []) as $id => $teks) {
            JenisSurat::whereKey($id)->update(['isi_surat' => trim((string) $teks) ?: null]);
        }

        return redirect()->route('admin.surat.pengaturan')->with('success', 'Pengaturan surat berhasil disimpan. Surat berikutnya memakai struktur baru.');
    }

    /** Kembalikan kop & kalimat ke bawaan (diambil ulang dari Profil Desa & Perangkat Desa). */
    public function reset()
    {
        $ps = PengaturanSurat::aktif();
        $ps->update(collect(PengaturanSurat::bawaan())->except(['format_nomor'])->all());

        return redirect()->route('admin.surat.pengaturan')->with('success', 'Kop surat & kalimat dikembalikan ke bawaan dari Profil Desa.');
    }

    /**
     * Pratinjau surat contoh. Lewat POST memakai isian form yang BELUM disimpan,
     * sehingga admin bisa melihat hasilnya dulu sebelum menyimpan.
     */
    public function pratinjau(Request $request)
    {
        $ps = PengaturanSurat::aktif();
        $jenis = JenisSurat::find($request->input('jenis_id')) ?? JenisSurat::orderBy('nama')->first();

        if ($request->isMethod('post')) {
            $ps = (clone $ps)->fill($this->validated($request));
            if ($jenis && $request->has('isi.' . $jenis->id)) {
                $jenis->isi_surat = $request->input('isi.' . $jenis->id);
            }
        }

        $surat = $this->suratContoh($jenis, $ps);
        $profil = ProfilDesa::first();

        if ($request->boolean('pdf')) {
            return Pdf::loadView('pdf.surat', compact('surat', 'profil', 'ps'))->setPaper('a4', 'portrait')
                ->stream('contoh-' . ($jenis->kode ?? 'surat') . '.pdf');
        }

        $layar = true; // tampilan lembar A4 di layar

        return view('pdf.surat', compact('surat', 'profil', 'ps', 'layar'));
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'kop_baris_1' => ['nullable', 'string', 'max:150'],
            'kop_baris_2' => ['nullable', 'string', 'max:150'],
            'kop_baris_3' => ['nullable', 'string', 'max:150'],
            'kop_alamat' => ['nullable', 'string', 'max:255'],
            'tempat_surat' => ['nullable', 'string', 'max:100'],
            'ttd_jabatan' => ['required', 'string', 'max:150'],
            'ttd_nama' => ['required', 'string', 'max:150'],
            'ttd_nip' => ['nullable', 'string', 'max:50'],
            'format_nomor' => ['required', 'string', 'max:100', 'regex:/\{nomor\}/'],
            'kalimat_pembuka' => ['nullable', 'string', 'max:2000'],
            'isi_bawaan' => ['nullable', 'string', 'max:3000'],
            'kalimat_penutup' => ['nullable', 'string', 'max:2000'],
            'isi' => ['nullable', 'array'],
            'isi.*' => ['nullable', 'string', 'max:3000'],
        ], [
            'ttd_jabatan.required' => 'Jabatan penandatangan wajib diisi.',
            'ttd_nama.required' => 'Nama penandatangan (kepala desa/lurah) wajib diisi.',
            'format_nomor.regex' => 'Format nomor harus memuat {nomor}.',
        ]);
        $data['tampilkan_logo'] = $request->boolean('tampilkan_logo');
        unset($data['isi']);

        return $data;
    }

    /** Surat contoh: surat nyata terakhir bila ada, atau data contoh (tidak disimpan). */
    private function suratContoh(?JenisSurat $jenis, PengaturanSurat $ps): Surat
    {
        $nyata = Surat::with('penduduk')->whereHas('penduduk')->latest()->first();
        $penduduk = $nyata?->penduduk ?? new Penduduk([
            'nama' => 'Contoh Nama Warga', 'nik' => '3504000000000001', 'tempat_lahir' => 'Tulungagung',
            'tanggal_lahir' => '1990-08-17', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Wiraswasta',
            'alamat' => 'Dusun Contoh', 'rt' => '01', 'rw' => '01',
        ]);

        $surat = new Surat(['keperluan' => $nyata->keperluan ?? 'Persyaratan administrasi', 'status' => 'selesai']);
        $surat->setRelation('penduduk', $penduduk);
        $surat->setRelation('jenisSurat', $jenis);
        $surat->no_surat = $ps->formatNomor(($ps->tahun_nomor === (int) now()->format('Y') ? $ps->nomor_terakhir : 0) + 1, $jenis);
        $surat->selesai_at = now();

        return $surat;
    }
}
