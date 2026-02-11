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
        'tanggal_dikembalikan', // Sesuaikan dengan kolom di database
        'status',
        'keterangan',
        'metode_pengembalian',
        'foto_bukti',
        'waktu_pengembalian', // Tambahkan ini
    ];

    protected $casts = [
        'alat_ids' => 'array',
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_dikembalikan' => 'datetime',
        'waktu_pengembalian' => 'datetime', // atau 'string' tergantung kebutuhan
    ];

    // Validasi status
    public static $statuses = ['menunggu', 'ditolak', 'dipinjam', 'selesai', 'ditegur'];

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

        // Jika status berubah dari 'menunggu' atau 'ditolak' ke 'dipinjam'
        if (($oldStatus === 'menunggu' || $oldStatus === 'ditolak') && $newStatus === 'dipinjam') {
            // Kurangi stok saat alat dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->decrement('stok');
            }
        }
        
        // Jika status berubah dari 'dipinjam' ke 'selesai'
        elseif ($oldStatus === 'dipinjam' && $newStatus === 'selesai') {
            // Tambah stok kembali saat alat selesai/dikembalikan
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
            
            // Set tanggal dikembalikan jika belum ada
            if (!$this->tanggal_dikembalikan) {
                $this->attributes['tanggal_dikembalikan'] = now();
            }
        }
        
        // Jika status berubah dari 'dipinjam' ke 'ditolak' (jika ada pembatalan)
        elseif ($oldStatus === 'dipinjam' && $newStatus === 'ditolak') {
            // Kembalikan stok karena dibatalkan setelah dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
        }
        
        // Jika status berubah dari 'selesai' ke status lain (rollback)
        elseif ($oldStatus === 'selesai' && $newStatus !== 'selesai') {
            // Kurangi stok kembali karena rollback dari selesai
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->decrement('stok');
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

    // Method untuk mengembalikan alat (ubah status ke selesai)
    public function kembalikanAlat($fotoPath = null, $metode = 'langsung')
    {
        $updateData = [
            'status' => 'selesai',
            'tanggal_dikembalikan' => now(),
            'waktu_pengembalian' => now(),
            'metode_pengembalian' => $metode,
        ];

        if ($fotoPath) {
            $updateData['foto_bukti'] = $fotoPath;
        }

        $this->update($updateData);
        
        return $this;
    }
}