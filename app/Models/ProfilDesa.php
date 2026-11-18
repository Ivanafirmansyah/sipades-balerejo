<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    protected $fillable = [
        'nama_desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'logo',
        'sejarah', 'visi', 'misi', 'deskripsi', 'alamat_kantor', 'telepon', 'email', 'peta_embed',
    ];

    public static function data(): ?self
    {
        return static::first();
    }

    /** "Desa Balerejo" -> "Balerejo" (mencegah tulisan ganda "Desa Desa ..." di surat). */
    public static function namaTanpaAwalan(?string $nama): string
    {
        return trim((string) preg_replace('/^(desa|kelurahan)\s+/i', '', (string) $nama));
    }
}
