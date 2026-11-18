<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Salinan data isian sebelum dikoreksi (audit trail koreksi berversi). */
class FormulirJawabanRiwayat extends Model
{
    protected $fillable = [
        'formulir_jawaban_id', 'versi', 'data', 'op_uuid', 'diubah_oleh', 'dikirim_offline', 'diubah_pada',
    ];

    protected $casts = [
        'data' => 'array',
        'dikirim_offline' => 'boolean',
        'diubah_pada' => 'datetime',
    ];

    public function jawaban()
    {
        return $this->belongsTo(FormulirJawaban::class, 'formulir_jawaban_id');
    }

    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
