<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'user_id', 'penduduk_id', 'jenis_surat_id', 'keperluan', 'keterangan', 'data',
        'status', 'no_surat', 'file_path', 'catatan_admin', 'diproses_at', 'selesai_at',
    ];

    protected $casts = [
        'data' => 'array',
        'diproses_at' => 'datetime',
        'selesai_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }
}
