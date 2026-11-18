<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'nik',
        'phone',
        'role',
        'penduduk_id',
        'password',
        'wajib_ganti_sandi',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wajib_ganti_sandi' => 'boolean',
        ];
    }
}
