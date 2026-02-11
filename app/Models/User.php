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
    'username',
    'role',
    'kelas_jurusan', // ganti dengan ini
    'is_blocked',
    'password',
    'plain_password',
];

public function getNamaJurusanLengkapAttribute()
{
    $singkatan = $this->jurusan; // Ambil dari kolom jurusan atau dari parsing kelas_jurusan
    
    $mapping = [
        'PPLG' => 'Pengembangan Perangkat Lunak dan Gim',
        'ANM' => 'Animasi',
        'BC' => 'Broadcasting',
        'TPFL' => 'Tata Panggung dan Film',
        'TO' => 'Teknik Otomotif',
    ];
    
    return $mapping[$singkatan] ?? $singkatan;
}

/// Atau jika menggunakan kelas_jurusan, tambahkan method parsing:
public function getSingkatanJurusanAttribute()
{
    if (!$this->kelas_jurusan) return null;
    
    $parts = explode(' ', $this->kelas_jurusan);
    return $parts[1] ?? null; // Ambil bagian tengah (jurusan)
}

public function getNomorKelasAttribute()
{
    if (!$this->kelas_jurusan) return null;
    
    $parts = explode(' ', $this->kelas_jurusan);
    return $parts[2] ?? null;
}

public function getJurusanAttribute()
{
    if (!$this->kelas_jurusan) return null;
    
    $parts = explode(' ', $this->kelas_jurusan);
    return $parts[1] ?? null;
}

public function getTingkatAttribute()
{
    if (!$this->kelas_jurusan) return null;
    
    $parts = explode(' ', $this->kelas_jurusan);
    return $parts[0] ?? null;
}

// Di App\Models\User.php
public function peminjaman()
{
    return $this->hasMany(Peminjaman::class);
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
        ];
    }

    // Tambahkan method untuk scope peminjam
    public function scopePeminjam($query)
    {
        return $query->where('role', 'peminjam');
    }

}