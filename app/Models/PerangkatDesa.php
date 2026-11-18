<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PerangkatDesa extends Model
{
    protected $fillable = ['nama', 'jabatan', 'atasan_id', 'urutan', 'is_mitra', 'foto', 'keterangan'];

    protected $casts = ['is_mitra' => 'boolean'];

    public function atasan()
    {
        return $this->belongsTo(self::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(self::class, 'atasan_id')->orderBy('urutan')->orderBy('id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /** Inisial nama untuk avatar, mis. "H. Ahmad Fauzi" -> "AF". */
    public function getInisialAttribute(): string
    {
        $kata = collect(preg_split('/\s+/', trim(preg_replace('/^(h|hj|drs?|ir|st)\.\s*/i', '', $this->nama))))->filter();

        return strtoupper(mb_substr($kata->first() ?? '?', 0, 1) . ($kata->count() > 1 ? mb_substr($kata->last(), 0, 1) : ''));
    }

    /**
     * Tebak posisi dalam struktur dari nama jabatan (Permendagri No. 84 Tahun 2015 tentang SOTK Pemerintah Desa):
     * Kepala Desa (akar) → Sekretaris Desa → Kaur; Kasi & Kepala Dusun langsung di bawah Kepala Desa; BPD = mitra.
     */
    public static function tebakPosisi(string $jabatan, ?int $kecuali = null): array
    {
        $j = mb_strtolower($jabatan);
        $cari = fn (string $pola) => static::where('jabatan', 'like', $pola)->when($kecuali, fn ($q) => $q->whereKeyNot($kecuali))->value('id');
        $kades = $cari('%kepala desa%');
        $sekdes = $cari('%sekretaris desa%');

        return match (true) {
            str_contains($j, 'bpd') || str_contains($j, 'permusyawaratan') || str_contains($j, 'lpm') => ['atasan_id' => null, 'urutan' => 0, 'is_mitra' => true],
            str_contains($j, 'kepala desa') || str_contains($j, 'lurah') => ['atasan_id' => null, 'urutan' => 0, 'is_mitra' => false],
            str_contains($j, 'sekretaris') => ['atasan_id' => $kades, 'urutan' => 1, 'is_mitra' => false],
            str_starts_with($j, 'kaur') || str_contains($j, 'kepala urusan') => ['atasan_id' => $sekdes ?? $kades, 'urutan' => 1, 'is_mitra' => false],
            str_starts_with($j, 'kasi') || str_contains($j, 'kepala seksi') => ['atasan_id' => $kades, 'urutan' => 2, 'is_mitra' => false],
            str_contains($j, 'kadus') || str_contains($j, 'kepala dusun') => ['atasan_id' => $kades, 'urutan' => 3, 'is_mitra' => false],
            default => ['atasan_id' => $kades, 'urutan' => 5, 'is_mitra' => false],
        };
    }

    /** ID diri sendiri + semua bawahan (untuk mencegah struktur melingkar). */
    public function idKeturunan(): array
    {
        $ids = [$this->id];
        foreach ($this->bawahan as $b) {
            $ids = array_merge($ids, $b->idKeturunan());
        }

        return $ids;
    }

    /** Akar pohon (tanpa atasan, bukan mitra) beserta seluruh bawahannya. */
    public static function pohon(): Collection
    {
        $semua = static::orderBy('urutan')->orderBy('id')->get();
        $anak = $semua->groupBy(fn ($p) => $p->atasan_id ?? 0);
        $semua->each(fn ($p) => $p->setRelation('bawahan', $anak->get($p->id, new Collection())));

        return $semua->filter(fn ($p) => ! $p->atasan_id && ! $p->is_mitra)->values();
    }
}
