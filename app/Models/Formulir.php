<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $fillable = [
        'judul', 'slug', 'tipe', 'kategori', 'jenis_surat_id', 'deskripsi', 'link',
        'schema', 'butuh_login', 'pesan_sukses', 'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'butuh_login' => 'boolean',
        'schema' => 'array',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function jawabans()
    {
        return $this->hasMany(FormulirJawaban::class);
    }

    public function isDinamis(): bool
    {
        return $this->tipe === 'dinamis';
    }

    public function isSurat(): bool
    {
        return $this->isDinamis() && $this->kategori === 'surat';
    }

    /** Daftar field yang bisa diisi (tanpa elemen tampilan seperti judul bagian). */
    public function fields(): array
    {
        return array_values(array_filter($this->schema['fields'] ?? [], fn ($f) => ($f['type'] ?? '') !== 'section'));
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
