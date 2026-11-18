<?php

namespace App\Models;

use App\Observers\PendudukObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(PendudukObserver::class)]
class Penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik', 'no_kk', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'agama', 'pekerjaan', 'alamat', 'rt', 'rw', 'dusun', 'status', 'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Umur dihitung langsung dari tanggal lahir setiap kali dibaca (tidak disimpan),
     * sehingga otomatis bertambah tepat di hari ulang tahun setiap tahun.
     */
    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    /** Umur siap tampil, mis. "34 tahun", atau "8 bulan" untuk bayi. */
    public function getUmurTeksAttribute(): string
    {
        if (! $this->tanggal_lahir) {
            return '-';
        }
        if ($this->umur === 0) {
            return max(0, (int) $this->tanggal_lahir->diffInMonths(now())) . ' bulan';
        }

        return $this->umur . ' tahun';
    }

    /** Kelompok umur (kategori umum data kependudukan). */
    public function getKelompokUmurAttribute(): ?string
    {
        $u = $this->umur;

        return match (true) {
            $u === null => null,
            $u < 5 => 'Balita',
            $u < 12 => 'Anak',
            $u < 18 => 'Remaja',
            $u < 60 => 'Dewasa',
            default => 'Lansia',
        };
    }
}
