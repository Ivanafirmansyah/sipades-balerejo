<?php

namespace App\Support;

use App\Models\JenisSurat;
use App\Models\PengaturanSurat;
use App\Models\ProfilDesa;
use App\Models\Surat;

/**
 * Mengisi kalimat surat yang ditulis admin dengan data surat.
 *
 * Kata kunci yang bisa dipakai: {nama} {nik} {ttl} {jenis_kelamin} {agama} {pekerjaan} {alamat}
 * {desa} {kecamatan} {kabupaten} {provinsi} {keperluan} {jabatan} {jenis_surat} {nomor} {tanggal}.
 * Teks diapit **dua bintang** dicetak tebal. Semua isi di-escape (aman dari HTML/script).
 */
class SuratTemplate
{
    public const KATA_KUNCI = [
        '{nama}' => 'Nama pemohon',
        '{nik}' => 'NIK pemohon',
        '{ttl}' => 'Tempat, tanggal lahir',
        '{jenis_kelamin}' => 'Laki-laki / Perempuan',
        '{agama}' => 'Agama',
        '{pekerjaan}' => 'Pekerjaan',
        '{alamat}' => 'Alamat lengkap (RT/RW)',
        '{desa}' => 'Nama desa (mis. Desa Balerejo)',
        '{kecamatan}' => 'Kecamatan',
        '{kabupaten}' => 'Kabupaten',
        '{provinsi}' => 'Provinsi',
        '{keperluan}' => 'Keperluan surat',
        '{jabatan}' => 'Jabatan penandatangan',
        '{jenis_surat}' => 'Nama jenis surat',
        '{nomor}' => 'Nomor surat',
        '{tanggal}' => 'Tanggal surat',
    ];

    public static function nilai(Surat $surat, PengaturanSurat $ps, ?ProfilDesa $profil): array
    {
        $p = $surat->penduduk;
        $desa = ProfilDesa::namaTanpaAwalan($profil?->nama_desa);
        $alamat = trim(($p?->alamat ?? '') . ' RT ' . ($p?->rt ?? '-') . ' / RW ' . ($p?->rw ?? '-')
            . ($p?->dusun ? ', Dusun ' . $p->dusun : ''));

        return [
            '{nama}' => $p?->nama ?? '-',
            '{nik}' => $p?->nik ?? '-',
            '{ttl}' => ($p?->tempat_lahir ?? '-') . ', ' . ($p?->tanggal_lahir?->translatedFormat('d F Y') ?? '-'),
            '{jenis_kelamin}' => ($p?->jenis_kelamin ?? '') === 'L' ? 'Laki-laki' : 'Perempuan',
            '{agama}' => $p?->agama ?? '-',
            '{pekerjaan}' => $p?->pekerjaan ?? '-',
            '{alamat}' => $alamat,
            '{desa}' => 'Desa ' . $desa,
            '{kecamatan}' => $profil?->kecamatan ?? '-',
            '{kabupaten}' => $profil?->kabupaten ?? '-',
            '{provinsi}' => $profil?->provinsi ?? '-',
            '{keperluan}' => $surat->keperluan ?: '-',
            '{jabatan}' => $ps->ttd_jabatan ?: 'Kepala Desa ' . $desa,
            '{jenis_surat}' => $surat->jenisSurat->nama ?? 'Surat Keterangan',
            '{nomor}' => $surat->no_surat ?: '…',
            '{tanggal}' => ($surat->selesai_at ?? now())->translatedFormat('d F Y'),
        ];
    }

    /** Teks template -> HTML aman (escape, ganti kata kunci, **tebal**, baris baru). */
    public static function render(?string $teks, array $nilai): string
    {
        $html = e((string) $teks);
        $html = strtr($html, array_map(fn ($v) => e((string) $v), $nilai));
        $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html);

        return nl2br($html, false);
    }

    /** Paragraf isi: isi khusus jenis surat, atau isi bawaan pengaturan. */
    public static function isi(?JenisSurat $jenis, PengaturanSurat $ps): string
    {
        return trim((string) $jenis?->isi_surat) !== '' ? $jenis->isi_surat : ($ps->isi_bawaan ?: PengaturanSurat::ISI_BAWAAN);
    }

    /** Logo desa sebagai data URI PNG (dompdf aman, juga untuk logo WebP hasil kompresi). */
    public static function logoDataUri(?ProfilDesa $profil): ?string
    {
        if (! $profil?->logo) {
            return null;
        }
        $path = storage_path('app/public/' . $profil->logo);
        if (! is_file($path) || ! ($img = @imagecreatefromstring(file_get_contents($path)))) {
            return null;
        }
        imagesavealpha($img, true);
        ob_start();
        imagepng($img);
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode(ob_get_clean());
    }
}
