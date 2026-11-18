<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use App\Models\FormulirJawaban;
use App\Models\FormulirJawabanRiwayat;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\Surat;
use App\Support\FormSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FormulirPublikController extends Controller
{
    public function index()
    {
        $profil = ProfilDesa::first();
        $formulirs = Formulir::where('is_aktif', true)->orderBy('tipe')->latest()->get();

        return view('formulir.index', compact('profil', 'formulirs'));
    }

    public function show(Request $request, string $slug)
    {
        $profil = ProfilDesa::first();
        $formulir = Formulir::where('slug', $slug)->where('tipe', 'dinamis')->where('is_aktif', true)->firstOrFail();

        // Isian milik warga yang login (dari perangkat mana pun) untuk fitur koreksi.
        // Hanya metadata (nomor, versi, status) — TANPA isi data pribadi, karena halaman ini
        // disimpan service worker untuk dipakai offline. Isi data diambil saat menekan "Koreksi".
        $isianSaya = $request->user()
            ? $formulir->jawabans()->with('surat', 'formulir')->where('user_id', $request->user()->id)->latest()->take(10)->get()
                ->map(fn ($j) => $this->ringkas($j, false))->values()
            : collect();

        return view('formulir.show', compact('profil', 'formulir', 'isianSaya'));
    }

    /**
     * Terima isian formulir. Dipakai untuk kirim langsung maupun sinkronisasi antrian offline.
     * Idempoten: client_uuid yang sama tidak akan disimpan dua kali.
     */
    public function kirim(Request $request, string $slug)
    {
        $formulir = Formulir::where('slug', $slug)->where('tipe', 'dinamis')->first();
        if (! $formulir || ! $formulir->is_aktif) {
            return $this->gagal($request, 'Formulir sudah tidak tersedia.', 410);
        }

        $user = $request->user();
        if ($formulir->butuh_login && ! $user) {
            return $this->gagal($request, 'Silakan masuk (login) terlebih dahulu. Isian Anda tetap tersimpan dan akan dikirim setelah login.', 401);
        }

        $request->validate([
            'client_uuid' => ['nullable', 'uuid'],
            'kunci_edit' => ['nullable', 'string', 'min:20', 'max:100'],
            'diisi_pada' => ['nullable', 'date'],
            'offline' => ['nullable', 'boolean'],
            'data' => ['array'],
        ]);

        // Sudah pernah diterima (mis. sinkronisasi diulang)? Balas sukses tanpa menyimpan lagi.
        if ($request->client_uuid && ($lama = FormulirJawaban::where('client_uuid', $request->client_uuid)->first())) {
            return $this->sukses($request, $formulir, $lama);
        }

        $fields = $formulir->fields();
        $request->validate(FormSchema::rules($fields), FormSchema::messages(), FormSchema::attributes($fields));
        $data = FormSchema::pick($fields, (array) $request->input('data', []));

        try {
            $jawaban = $this->simpan($request, $formulir, $user, $data, $fields, $this->waktuPerangkat($request->diisi_pada));
        } catch (\Illuminate\Database\UniqueConstraintViolationException) {
            // Dua sinkronisasi bersamaan untuk isian yang sama: yang kedua cukup dianggap sukses.
            $jawaban = FormulirJawaban::where('client_uuid', $request->client_uuid)->firstOrFail();
        }

        return $this->sukses($request, $formulir, $jawaban);
    }

    /**
     * KOREKSI ISIAN BERVERSI (optimistic concurrency control).
     *
     * Perangkat mengirim versi data yang terakhir ia lihat (versi_dasar). Perubahan hanya disimpan
     * bila versi di server masih sama; jika sudah berubah (dikoreksi di perangkat lain / diproses petugas)
     * server membalas 409 dan TIDAK menimpa apa pun. Setiap koreksi menyimpan salinan data lama (riwayat).
     * Idempoten lewat op_uuid: koreksi yang sama tidak tercatat dua kali saat sinkronisasi diulang.
     */
    public function ubah(Request $request, string $slug)
    {
        $request->validate([
            'client_uuid' => ['required', 'uuid'],
            'op_uuid' => ['required', 'uuid'],
            'versi_dasar' => ['required', 'integer', 'min:1'],
            'kunci_edit' => ['nullable', 'string', 'max:100'],
            'diubah_pada' => ['nullable', 'date'],
            'offline' => ['nullable', 'boolean'],
            'data' => ['array'],
        ]);

        $jawaban = FormulirJawaban::with('formulir', 'surat')->where('client_uuid', $request->client_uuid)->first();
        if (! $jawaban || $jawaban->formulir?->slug !== $slug) {
            return response()->json(['ok' => false, 'pesan' => 'Isian tidak ditemukan.'], 404);
        }
        if (! $jawaban->milik($request->user(), $request->kunci_edit)) {
            return response()->json(['ok' => false, 'pesan' => 'Anda tidak berhak mengoreksi isian ini.'], 403);
        }

        // Koreksi ini sudah pernah diterapkan (sinkronisasi diulang) -> balas sukses tanpa mengubah lagi
        if (FormulirJawabanRiwayat::where('op_uuid', $request->op_uuid)->exists()) {
            return response()->json(['ok' => true, 'pesan' => 'Perubahan sudah tersimpan.'] + $this->ringkas($jawaban->fresh(['formulir', 'surat'])));
        }

        if (! $jawaban->bisaDikoreksi()) {
            return response()->json([
                'ok' => false, 'konflik' => 'terkunci',
                'pesan' => 'Isian sudah diproses petugas desa sehingga tidak bisa dikoreksi lagi. Hubungi kantor desa bila ada yang salah.',
            ] + $this->ringkas($jawaban), 409);
        }

        $formulir = $jawaban->formulir;
        $fields = $formulir->fields();
        $request->validate(FormSchema::rules($fields), FormSchema::messages(), FormSchema::attributes($fields));
        $data = FormSchema::pick($fields, (array) $request->input('data', []));

        try {
            $hasil = $this->terapkanKoreksi($request, $jawaban, $formulir, $fields, $data);
        } catch (\Illuminate\Database\UniqueConstraintViolationException) {
            // Dua sinkronisasi bersamaan untuk koreksi yang sama: yang kedua dianggap sudah tersimpan
            return response()->json(['ok' => true, 'pesan' => 'Perubahan sudah tersimpan.'] + $this->ringkas($jawaban->fresh(['formulir', 'surat'])));
        }

        $terbaru = $jawaban->fresh(['formulir', 'surat']);

        if (! $hasil) {
            return response()->json([
                'ok' => false, 'konflik' => 'versi',
                'pesan' => 'Isian ini sudah diubah dari perangkat lain sejak terakhir Anda membukanya. Periksa data terbaru lalu koreksi ulang.',
            ] + $this->ringkas($terbaru), 409);
        }

        return response()->json(['ok' => true, 'pesan' => 'Perubahan isian berhasil disimpan.'] + $this->ringkas($terbaru));
    }

    /** Compare-and-swap versi + simpan riwayat, dalam satu transaksi. False bila versi sudah berubah. */
    private function terapkanKoreksi(Request $request, FormulirJawaban $jawaban, Formulir $formulir, array $fields, array $data): bool
    {
        return DB::transaction(function () use ($request, $jawaban, $formulir, $fields, $data) {
            $dataLama = $jawaban->data;
            $versiLama = (int) $request->versi_dasar;

            // Compare-and-swap: hanya berhasil bila versi di server masih versi_dasar
            $berubah = FormulirJawaban::whereKey($jawaban->id)->where('versi', $versiLama)->update([
                'data' => json_encode($data),
                'versi' => $versiLama + 1,
                'diedit_kali' => DB::raw('diedit_kali + 1'),
                'terakhir_diedit_at' => now(),
                'updated_at' => now(),
            ]);
            if ($berubah === 0) {
                return false;
            }

            FormulirJawabanRiwayat::create([
                'formulir_jawaban_id' => $jawaban->id,
                'versi' => $versiLama,
                'data' => $dataLama,
                'op_uuid' => $request->op_uuid,
                'diubah_oleh' => $request->user()?->id,
                'dikirim_offline' => $request->boolean('offline'),
                'diubah_pada' => $this->waktuPerangkat($request->diubah_pada),
            ]);

            if ($jawaban->surat && $jawaban->surat->status === 'menunggu') {
                $jawaban->surat->update([
                    'keperluan' => $this->keperluan($fields, $data) ?? $formulir->judul,
                    'data' => $this->dataSurat($fields, $data),
                ]);
            }

            return true;
        });
    }

    /** Data terbaru sebuah isian di server (untuk menyelesaikan konflik). Hanya untuk pengirimnya. */
    public function isian(Request $request, string $slug, string $uuid)
    {
        $jawaban = FormulirJawaban::with('formulir', 'surat')->where('client_uuid', $uuid)->first();
        if (! $jawaban || $jawaban->formulir?->slug !== $slug) {
            return response()->json(['ok' => false, 'pesan' => 'Isian tidak ditemukan.'], 404);
        }
        if (! $jawaban->milik($request->user(), $request->header('X-Kunci-Edit'))) {
            return response()->json(['ok' => false, 'pesan' => 'Anda tidak berhak melihat isian ini.'], 403);
        }

        return response()->json(['ok' => true] + $this->ringkas($jawaban));
    }

    /* ---------------- Helper ---------------- */

    private function ringkas(FormulirJawaban $j, bool $denganData = true): array
    {
        return [
            'uuid' => $j->client_uuid,
            'nomor' => $j->id,
            'versi' => $j->versi,
            'data' => $denganData ? $j->data : null,
            'status' => $j->statusLabel(),
            'bisa_dikoreksi' => $j->bisaDikoreksi(),
            'diedit_kali' => $j->diedit_kali,
            'dikirim' => $j->created_at?->toIso8601String(),
        ];
    }

    private function waktuPerangkat(?string $waktu): Carbon
    {
        $w = $waktu ? Carbon::parse($waktu) : now();

        return $w->isFuture() ? now() : $w; // jam HP bisa salah: waktu di masa depan diabaikan
    }

    private function dataSurat(array $fields, array $data): array
    {
        return array_map(fn ($f) => ['label' => $f['label'], 'nilai' => FormSchema::display($f, $data[$f['name']] ?? null)], $fields);
    }

    private function simpan(Request $request, Formulir $formulir, $user, array $data, array $fields, Carbon $diisi): FormulirJawaban
    {
        return DB::transaction(function () use ($request, $formulir, $user, $data, $fields, $diisi) {
            $surat = null;
            if ($formulir->isSurat()) {
                $penduduk = $this->cariPenduduk($user, $fields, $data);
                $surat = Surat::create([
                    'user_id' => $user->id,
                    'penduduk_id' => $penduduk->id,
                    'jenis_surat_id' => $formulir->jenis_surat_id,
                    'keperluan' => $this->keperluan($fields, $data) ?? $formulir->judul,
                    'keterangan' => 'Diajukan melalui formulir online "' . $formulir->judul . '"',
                    'data' => $this->dataSurat($fields, $data),
                    'status' => 'menunggu',
                ]);
            }

            return FormulirJawaban::create([
                'formulir_id' => $formulir->id,
                'user_id' => $user?->id,
                'surat_id' => $surat?->id,
                'client_uuid' => $request->client_uuid,
                // Kunci edit dibuat di perangkat; server hanya menyimpan hash-nya
                'kunci_edit' => $request->kunci_edit ? hash('sha256', $request->kunci_edit) : null,
                'data' => $data,
                'versi' => 1,
                'status' => 'baru',
                'diedit_kali' => 0,
                'dikirim_offline' => $request->boolean('offline'),
                'diisi_pada' => $diisi,
                'ip_address' => $request->ip(),
            ]);
        });
    }

    private function cariPenduduk($user, array $fields, array $data): Penduduk
    {
        $penduduk = $user->penduduk ?? Penduduk::where('user_id', $user->id)->first();

        if (! $penduduk) {
            foreach ($fields as $f) {
                if ($f['type'] === 'nik' && ! empty($data[$f['name']])) {
                    $penduduk = Penduduk::where('nik', $data[$f['name']])->first();
                    break;
                }
            }
        }

        if (! $penduduk) {
            throw ValidationException::withMessages([
                'data' => 'Data kependudukan Anda belum terdaftar. Ajukan sekali melalui menu "Pengajuan Surat" atau hubungi kantor desa.',
            ]);
        }

        return $penduduk;
    }

    private function keperluan(array $fields, array $data): ?string
    {
        foreach ($fields as $f) {
            if (str_contains($f['name'], 'keperluan') && ! empty($data[$f['name']])) {
                return \Illuminate\Support\Str::limit((string) $data[$f['name']], 250, '');
            }
        }

        return null;
    }

    private function sukses(Request $request, Formulir $formulir, FormulirJawaban $jawaban)
    {
        $pesan = $formulir->pesan_sukses ?: 'Terima kasih, isian Anda sudah kami terima.';
        if ($jawaban->surat_id) {
            $pesan .= ' Pengajuan surat dapat dipantau di menu Riwayat Surat.';
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'pesan' => $pesan] + $this->ringkas($jawaban->loadMissing('formulir', 'surat')));
        }

        return redirect()->route('formulir.show', $formulir->slug)->with('success', $pesan);
    }

    private function gagal(Request $request, string $pesan, int $status)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => false, 'pesan' => $pesan], $status);
        }

        return $status === 401 ? redirect()->route('login')->with('error', $pesan) : back()->with('error', $pesan);
    }
}
