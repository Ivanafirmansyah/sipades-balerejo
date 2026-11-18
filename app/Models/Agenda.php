<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'tanggal', 'jam', 'lokasi', 'foto'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function getJamFormattedAttribute(): ?string
    {
        return $this->jam ? \Illuminate\Support\Carbon::parse($this->jam)->format('H:i') : null;
    }

    /** Alamat foto kegiatan, atau null bila belum ada foto. */
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }
}
