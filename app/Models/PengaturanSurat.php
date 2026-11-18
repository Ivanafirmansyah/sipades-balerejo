<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/** Pengaturan struktur isi surat (satu baris). */
class PengaturanSurat extends Model
{
    protected $fillable = [
        'kop_baris_1', 'kop_baris_2', 'kop_baris_3', 'kop_alamat', 'tampilkan_logo',
        'tempat_surat', 'ttd_jabatan', 'ttd_nama', 'ttd_nip',
        'format_nomor', 'nomor_terakhir', 'tahun_nomor',
        'kalimat_pembuka', 'isi_bawaan', 'kalimat_penutup',
    ];

    protected $casts = ['tampilkan_logo' => 'boolean'];

    public const KALIMAT_PEMBUKA = 'Yang bertanda tangan di bawah ini {jabatan}, Kecamatan {kecamatan}, Kabupaten {kabupaten}, Provinsi {provinsi}, dengan ini menerangkan bahwa:';
    public const ISI_BAWAAN = 'Adalah benar warga {desa} yang berdomisili di alamat tersebut di atas. Surat keterangan ini dibuat untuk keperluan **{keperluan}**.';
    public const KALIMAT_PENUTUP = 'Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.';

    /** Pengaturan yang berlaku; dibuat otomatis dari profil desa bila belum ada. */
    public static function aktif(): self
    {
        return static::first() ?? static::create(static::bawaan());
    }

    /** Nilai awal yang diambil dari profil desa & perangkat desa. */
    public static function bawaan(): array
    {
        $profil = ProfilDesa::first();
        $desa = ProfilDesa::namaTanpaAwalan($profil?->nama_desa);
        $kades = PerangkatDesa::where('jabatan', 'like', '%Kepala Desa%')->first();

        return [
            'kop_baris_1' => 'PEMERINTAH KABUPATEN ' . mb_strtoupper($profil?->kabupaten ?? ''),
            'kop_baris_2' => 'KECAMATAN ' . mb_strtoupper($profil?->kecamatan ?? ''),
            'kop_baris_3' => 'DESA ' . mb_strtoupper($desa),
            'kop_alamat' => trim(($profil?->alamat_kantor ?? '') . ($profil?->kode_pos ? ' Kode Pos ' . $profil->kode_pos : '')),
            'tampilkan_logo' => true,
            'tempat_surat' => $desa,
            'ttd_jabatan' => 'Kepala Desa ' . $desa,
            'ttd_nama' => $kades?->nama,
            'format_nomor' => '470/{nomor}/{kode}/{bulan_romawi}/{tahun}',
            'kalimat_pembuka' => self::KALIMAT_PEMBUKA,
            'isi_bawaan' => self::ISI_BAWAAN,
            'kalimat_penutup' => self::KALIMAT_PENUTUP,
        ];
    }

    /**
     * Buat nomor surat berikutnya sesuai format, mis. 470/007/SKD/XI/2026.
     * Urutan nomor dimulai lagi dari 1 setiap tahun. Aman dari nomor ganda (dikunci saat dibuat).
     */
    public function nomorBerikutnya(?JenisSurat $jenis, ?\DateTimeInterface $tanggal = null): string
    {
        $tanggal ??= now();
        $tahun = (int) $tanggal->format('Y');

        $urut = DB::transaction(function () use ($tahun) {
            $row = static::whereKey($this->id)->lockForUpdate()->first();
            $urut = $row->tahun_nomor === $tahun ? $row->nomor_terakhir + 1 : 1;
            $row->forceFill(['nomor_terakhir' => $urut, 'tahun_nomor' => $tahun])->save();

            return $urut;
        });

        return $this->formatNomor($urut, $jenis, $tanggal);
    }

    /** Contoh / hasil format nomor tanpa menaikkan urutan. */
    public function formatNomor(int $urut, ?JenisSurat $jenis, ?\DateTimeInterface $tanggal = null): string
    {
        $tanggal ??= now();
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][(int) $tanggal->format('n') - 1];

        return strtr($this->format_nomor ?: '{nomor}/{kode}/{tahun}', [
            '{nomor}' => str_pad((string) $urut, 3, '0', STR_PAD_LEFT),
            '{kode}' => $jenis?->kode ?: 'SK',
            '{bulan_romawi}' => $romawi,
            '{bulan}' => $tanggal->format('m'),
            '{tahun}' => $tanggal->format('Y'),
        ]);
    }
}
