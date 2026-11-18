<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = ['nama', 'kode', 'keterangan', 'isi_surat', 'is_aktif'];

    protected $casts = ['is_aktif' => 'boolean'];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
