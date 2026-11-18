<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Titik fasilitas umum di Peta Desa. */
class LokasiDesa extends Model
{
    protected $fillable = ['nama', 'kategori', 'keterangan', 'alamat', 'lat', 'lng', 'foto', 'is_utama', 'is_aktif'];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'is_utama' => 'boolean',
        'is_aktif' => 'boolean',
    ];

    /** Kategori fasilitas: label & ikon (Bootstrap Icons). */
    public const KATEGORI = [
        'pemerintahan' => ['Pemerintahan', 'bi-bank2'],
        'ibadah' => ['Tempat Ibadah', 'bi-moon-stars-fill'],
        'pendidikan' => ['Pendidikan', 'bi-mortarboard-fill'],
        'kesehatan' => ['Kesehatan', 'bi-heart-pulse-fill'],
        'umum' => ['Fasilitas Umum', 'bi-tree-fill'],
        'ekonomi' => ['Ekonomi & UMKM', 'bi-shop'],
        'wisata' => ['Wisata', 'bi-camera-fill'],
        'lainnya' => ['Lainnya', 'bi-geo-alt-fill'],
    ];

    /** Pusat peta bawaan (Desa Balerejo, Tulungagung — dari OpenStreetMap) bila belum ada titik utama. */
    public const PUSAT_BAWAAN = [-8.0608936, 111.8729609];

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori][0] ?? 'Lainnya';
    }

    public function getIkonAttribute(): string
    {
        return self::KATEGORI[$this->kategori][1] ?? 'bi-geo-alt-fill';
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /** Tautan petunjuk arah ke Google Maps. */
    public function getArahUrlAttribute(): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination=' . $this->lat . ',' . $this->lng;
    }

    /** Pusat peta: titik utama, atau titik pertama, atau pusat bawaan desa. */
    public static function pusat(): array
    {
        $utama = static::where('is_aktif', true)->orderByDesc('is_utama')->oldest()->first();

        return $utama ? [$utama->lat, $utama->lng] : self::PUSAT_BAWAAN;
    }

    /** Data ringkas untuk peta (JSON). */
    public function untukPeta(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'label' => $this->kategori_label,
            'ikon' => $this->ikon,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'alamat' => $this->alamat,
            'keterangan' => $this->keterangan,
            'foto' => $this->foto_url,
            'utama' => $this->is_utama,
            'arah' => $this->arah_url,
        ];
    }
}
