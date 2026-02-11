<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'alat_ids',
        'nama_alat',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'tanggal_dikembalikan',
        'status',
        'keterangan',
        'metode_pengembalian',
        'foto_bukti',
        'waktu_pengembalian',
    ];

    protected $casts = [
        'alat_ids' => 'array',
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_dikembalikan' => 'datetime',
        'waktu_pengembalian' => 'datetime',
    ];

    // PERUBAHAN: Pisahkan status untuk peminjaman dan pengembalian
    public static $statuses = [
        'menunggu_peminjaman',  // Menunggu persetujuan peminjaman
        'menunggu_pengembalian', // Menunggu konfirmasi pengembalian
        'ditolak',
        'dipinjam',
        'selesai',
        'ditegur'
    ];

    // Setter untuk status dengan auto update stok
    public function setStatusAttribute($value)
    {
        $oldStatus = $this->attributes['status'] ?? null;
        
        if (!in_array($value, self::$statuses)) {
            throw new \InvalidArgumentException("Status tidak valid. Status yang diperbolehkan: " . implode(', ', self::$statuses));
        }
        
        $this->attributes['status'] = $value;
        
        // Otomatis update stok saat status berubah
        if ($oldStatus !== $value) {
            $this->updateStokAlat($oldStatus, $value);
        }
    }

    // Method untuk update stok alat
    protected function updateStokAlat($oldStatus, $newStatus)
    {
        if (!$this->alat_ids || !is_array($this->alat_ids)) {
            return;
        }

        // Jika status berubah ke 'dipinjam' (disetujui)
        if ($newStatus === 'dipinjam') {
            // Kurangi stok saat alat disetujui untuk dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->decrement('stok');
            }
        }
        
        // Jika status berubah ke 'selesai' (pengembalian dikonfirmasi)
        elseif ($newStatus === 'selesai') {
            // Tambah stok kembali saat alat selesai/dikembalikan
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
            
            // Set tanggal dikembalikan jika belum ada
            if (!$this->tanggal_dikembalikan) {
                $this->attributes['tanggal_dikembalikan'] = now();
            }
        }
        
        // Jika status dibatalkan dari 'dipinjam' ke status lain
        elseif ($oldStatus === 'dipinjam' && $newStatus !== 'selesai') {
            // Kembalikan stok karena dibatalkan setelah dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
        }
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        if (!$this->alat_ids) {
            return collect();
        }
        return Alat::whereIn('id', $this->alat_ids)->get();
    }

    // Accessor untuk foto bukti
    public function getFotoBuktiUrlAttribute()
    {
        if ($this->foto_bukti) {
            return asset('storage/' . $this->foto_bukti);
        }
        return null;
    }

    // Helper method untuk cek status
    public function isMenungguPeminjaman()
    {
        return $this->status === 'menunggu_peminjaman';
    }
    
    public function isMenungguPengembalian()
    {
        return $this->status === 'menunggu_pengembalian';
    }
}