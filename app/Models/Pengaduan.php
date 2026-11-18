<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'user_id', 'nama_pelapor', 'kategori', 'isi', 'lokasi', 'status', 'tanggapan', 'tanggapan_at',
    ];

    protected $casts = ['tanggapan_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            default => $this->status,
        };
    }
}
