<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formulir;
use App\Models\FormulirJawaban;
use App\Models\JenisSurat;
use App\Support\FormSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FormulirController extends Controller
{
    public function index()
    {
        $formulirs = Formulir::withCount('jawabans')->latest()->paginate(10);

        return view('admin.formulir.index', compact('formulirs'));
    }

    public function create()
    {
        return view('admin.formulir.form', [
            'formulir' => new Formulir(['tipe' => 'dinamis', 'kategori' => 'pendataan', 'is_aktif' => true]),
            'jenisSurats' => JenisSurat::where('is_aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $formulir = Formulir::create($this->validated($request));

        return redirect()->route('admin.formulir.index')->with('success', "Formulir \"{$formulir->judul}\" berhasil dibuat.");
    }

    public function edit(Formulir $formulir)
    {
        return view('admin.formulir.form', [
            'formulir' => $formulir,
            'jenisSurats' => JenisSurat::where('is_aktif', true)->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Formulir $formulir)
    {
        $formulir->update($this->validated($request, $formulir));

        return redirect()->route('admin.formulir.index')->with('success', 'Formulir berhasil diperbarui.');
    }

    public function destroy(Formulir $formulir)
    {
        $formulir->delete();

        return back()->with('success', 'Formulir berhasil dihapus.');
    }

    /* ---------------- Isian / jawaban warga ---------------- */

    public function jawaban(Request $request, Formulir $formulir)
    {
        $jawabans = $formulir->jawabans()->with('user', 'surat', 'riwayats.pengubah')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => $formulir->jawabans()->count(),
            'baru' => $formulir->jawabans()->where('status', 'baru')->count(),
            'offline' => $formulir->jawabans()->where('dikirim_offline', true)->count(),
        ];

        return view('admin.formulir.jawaban', compact('formulir', 'jawabans', 'stats'));
    }

    public function jawabanUpdate(Request $request, Formulir $formulir, FormulirJawaban $jawaban)
    {
        abort_unless($jawaban->formulir_id === $formulir->id, 404);
        $status = $request->validate(['status' => ['required', Rule::in(['baru', 'diproses', 'selesai'])]])['status'];
        if ($status !== $jawaban->status) {
            // Perubahan status oleh petugas juga menaikkan versi, sehingga koreksi lama dari perangkat warga
            // yang datang belakangan tidak bisa menimpa isian yang sudah ditangani (balas 409).
            $jawaban->forceFill(['status' => $status, 'versi' => $jawaban->versi + 1])->save();
        }

        return back()->with('success', 'Status isian diperbarui.');
    }

    public function jawabanDestroy(Formulir $formulir, FormulirJawaban $jawaban)
    {
        abort_unless($jawaban->formulir_id === $formulir->id, 404);
        $jawaban->delete();

        return back()->with('success', 'Isian dihapus.');
    }

    /** Unduh semua isian sebagai CSV (bisa dibuka di Excel). */
    public function jawabanCsv(Formulir $formulir)
    {
        $fields = $formulir->fields();
        $file = 'isian-' . Str::slug($formulir->judul) . '-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($formulir, $fields) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['No', 'Waktu Masuk', 'Diisi Pada', 'Pengirim', 'Offline', 'Status', ...array_column($fields, 'label')], ';');
            $no = 1;
            $formulir->jawabans()->with('user')->oldest()->chunk(200, function ($rows) use ($out, $fields, &$no) {
                foreach ($rows as $j) {
                    fputcsv($out, [
                        $no++, $j->created_at->format('d/m/Y H:i'), $j->diisi_pada?->format('d/m/Y H:i') ?? '-',
                        $j->user->name ?? 'Tamu', $j->dikirim_offline ? 'Ya' : 'Tidak', $j->statusLabel(),
                        ...array_map(function ($f) use ($j) {
                            $v = FormSchema::display($f, $j->data[$f['name']] ?? null);
                            // NIK & no. HP: paksa jadi teks agar Excel tidak mengubahnya ke 3,2E+15 / membuang angka 0
                            return in_array($f['type'], ['nik', 'tel'], true) && $v !== '-' ? '="' . $v . '"' : $v;
                        }, $fields),
                    ], ';');
                }
            });
            fclose($out);
        }, $file, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /* ---------------- Helper ---------------- */

    private function validated(Request $request, ?Formulir $formulir = null): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'tipe' => ['required', Rule::in(['tautan', 'dinamis'])],
            'kategori' => ['required_if:tipe,dinamis', 'nullable', Rule::in(['pendataan', 'surat'])],
            'jenis_surat_id' => ['nullable', 'required_if:kategori,surat', 'exists:jenis_surats,id'],
            'link' => ['nullable', 'required_if:tipe,tautan', 'url', 'max:255'],
            'schema' => ['nullable', 'required_if:tipe,dinamis', 'string'],
            'pesan_sukses' => ['nullable', 'string', 'max:255'],
        ], [
            'link.required_if' => 'Link wajib diisi untuk formulir tautan.',
            'jenis_surat_id.required_if' => 'Pilih jenis surat untuk formulir pengajuan surat.',
        ], ['jenis_surat_id' => 'jenis surat']);

        $data['is_aktif'] = $request->boolean('is_aktif');
        $data['butuh_login'] = $request->boolean('butuh_login');

        if ($data['tipe'] === 'dinamis') {
            $data['schema'] = FormSchema::normalize($data['schema'] ?? '');
            if (count(array_filter($data['schema']['fields'], fn ($f) => $f['type'] !== 'section')) === 0) {
                throw \Illuminate\Validation\ValidationException::withMessages(['schema' => 'Tambahkan minimal satu isian pada formulir.']);
            }
            $data['link'] = null;
            if ($data['kategori'] === 'surat') {
                $data['butuh_login'] = true; // surat harus atas nama warga yang login
            } else {
                $data['jenis_surat_id'] = null;
            }
        } else {
            $data['schema'] = null;
            $data['kategori'] = 'pendataan';
            $data['jenis_surat_id'] = null;
        }

        // Slug unik untuk alamat formulir publik (tetap setelah dibuat agar tautan & cache offline tidak rusak)
        if (! $formulir?->slug) {
            $base = Str::slug($data['judul']) ?: 'formulir';
            $slug = $base;
            for ($i = 2; Formulir::where('slug', $slug)->when($formulir, fn ($q) => $q->whereKeyNot($formulir->id))->exists(); $i++) {
                $slug = $base . '-' . $i;
            }
            $data['slug'] = $slug;
        }

        return $data;
    }
}
