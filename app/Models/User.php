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
    'is_blocked',
        'blocked_reason',
        'blocked_at',
        'unblocked_at',
    'kelas_jurusan', // ganti dengan ini
    'password',
    'plain_password',
];
public function getTotalHariTelat()
    {
        $peminjamanTelat = $this->peminjaman()
            ->where('status', 'selesai')
            ->whereRaw('DATE(tanggal_dikembalikan) > DATE(tanggal_pengembalian)')
            ->get();
        
        $totalHari = 0;
        foreach ($peminjamanTelat as $p) {
            $jatuhTempo = \Carbon\Carbon::parse($p->tanggal_pengembalian);
            $dikembalikan = \Carbon\Carbon::parse($p->tanggal_dikembalikan);
            $totalHari += $jatuhTempo->diffInDays($dikembalikan);
        }
        
        return $totalHari;
    }
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

 // App\Models\User.php

public function blockBecauseTelat($alasan = null)
{
    // HITUNG JUMLAH PEMINJAMAN TELAT YANG MASIH DIPINJAM (BUKAN YANG SUDAH SELESAI)
    $peminjamanTelatAktif = $this->peminjaman()
        ->where('status', 'dipinjam')
        ->whereDate('tanggal_pengembalian', '<', \Carbon\Carbon::today())
        ->get();
    
    $jumlahPeminjaman = $peminjamanTelatAktif->count();
    $totalHari = 0;
    
    foreach ($peminjamanTelatAktif as $p) {
        $jatuhTempo = \Carbon\Carbon::parse($p->tanggal_pengembalian);
        $hariIni = \Carbon\Carbon::today();
        $totalHari += $jatuhTempo->diffInDays($hariIni);
    }
    
    $this->update([
        'is_blocked' => true,
        'blocked_reason' => $alasan ?? "Akun diblokir otomatis karena memiliki {$jumlahPeminjaman} peminjaman telat dengan total {$totalHari} hari keterlambatan.",
        'blocked_at' => now(),
    ]);
    
    // Log aktivitas blokir
    \App\Models\LogAktivitas::create([
        'user_id' => $this->id,
        'role' => $this->role,
        'aktivitas' => 'Akun diblokir otomatis - Peminjaman telat',
        'modul' => 'sistem',
        'data' => json_encode([
            'total_peminjaman_telat' => $jumlahPeminjaman,
            'total_hari_telat' => $totalHari,
            'blocked_at' => now()->toDateTimeString()
        ])
    ]);
    
    return $this;
}

/**
 * CEK APAKAH USER MASIH MEMILIKI PEMINJAMAN TELAT AKTIF
 */
public function masihMemilikiPeminjamanTelat()
{
    return $this->peminjaman()
        ->where('status', 'dipinjam')
        ->whereDate('tanggal_pengembalian', '<', \Carbon\Carbon::today())
        ->exists();
}

/**
 * UNBLOCK USER - HANYA JIKA TIDAK ADA PEMINJAMAN TELAT AKTIF
 */
public function unblock($alasan = null)
{
    // CEK ULANG APAKAH MASIH ADA PEMINJAMAN TELAT
    if ($this->masihMemilikiPeminjamanTelat()) {
        return false; // Gagal unblock karena masih ada peminjaman telat
    }
    
    $this->update([
        'is_blocked' => false,
        'blocked_reason' => null,
        'blocked_at' => null,
        'unblocked_at' => now(),
    ]);
    
    // Log pemulihan
    \App\Models\LogAktivitas::create([
        'user_id' => $this->id,
        'role' => $this->role,
        'aktivitas' => 'Akun dipulihkan - ' . ($alasan ?? 'Manual oleh petugas'),
        'modul' => 'user_management',
        'data' => json_encode([
            'unblocked_at' => now()->toDateTimeString(),
            'alasan' => $alasan
        ])
    ]);
    
    return true;
}


    /**
     * Relasi ke peminjaman
     */

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

     protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
        'unblocked_at' => 'datetime',
    ];
    public function getRiwayatPengembalianTelat()
    {
        return $this->peminjaman()
            ->where('status', 'selesai')
            ->whereRaw('DATE(tanggal_dikembalikan) > DATE(tanggal_pengembalian)')
            ->orderBy('tanggal_dikembalikan', 'desc')
            ->get();
    }


    public function hasPengembalianTelat()
    {
        return $this->peminjaman()
            ->where('status', 'selesai')
            ->whereRaw('DATE(tanggal_dikembalikan) > DATE(tanggal_pengembalian)')
            ->exists();
    }

    // Tambahkan method untuk scope peminjam
    public function scopePeminjam($query)
    {
        return $query->where('role', 'peminjam');
    }

}