<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulirJawaban extends Model
{
    protected $fillable = [
        'formulir_id', 'user_id', 'surat_id', 'client_uuid', 'kunci_edit', 'data', 'versi', 'diedit_kali', 'status',
        'dikirim_offline', 'diisi_pada', 'terakhir_diedit_at', 'ip_address',
    ];

    protected $hidden = ['kunci_edit'];

    protected $casts = [
        'data' => 'array',
        'dikirim_offline' => 'boolean',
        'diisi_pada' => 'datetime',
        'terakhir_diedit_at' => 'datetime',
    ];

    public function formulir()
    {
        return $this->belongsTo(Formulir::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function riwayats()
    {
        return $this->hasMany(FormulirJawabanRiwayat::class)->orderByDesc('versi');
    }

    public function statusLabel(): string
    {
        return ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'][$this->status ?? 'baru'] ?? (string) $this->status;
    }

    /** Masih boleh dikoreksi pengirim? Hanya selama belum diproses petugas. */
    public function bisaDikoreksi(): bool
    {
        if ($this->status !== 'baru' || ! $this->formulir?->is_aktif) {
            return false;
        }

        return ! $this->surat || $this->surat->status === 'menunggu';
    }

    /** Apakah peminta adalah pengirim isian ini (akun yang sama, atau memegang kunci edit dari perangkatnya)? */
    public function milik(?User $user, ?string $kunciEdit): bool
    {
        if ($user && $this->user_id && $this->user_id === $user->id) {
            return true;
        }

        return $kunciEdit !== null && $this->kunci_edit !== null && hash_equals($this->kunci_edit, hash('sha256', $kunciEdit));
    }
}
