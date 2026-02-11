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

    // PERUBAHAN: Hanya 4 status utama
    public static $statuses = [
        'menunggu_peminjaman',  // Menunggu persetujuan
        'dipinjam',             // Disetujui, sedang dipinjam
        'selesai',              // Sudah dikembalikan dan selesai
        'ditolak',              // Ditolak
        'ditegur'               // Ditegur
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

    protected static function boot()
    {
        parent::boot();
        
        static::updated(function ($peminjaman) {
            \Log::info('Peminjaman updated:', [
                'id' => $peminjaman->id,
                'old_status' => $peminjaman->getOriginal('status'),
                'new_status' => $peminjaman->status,
                'alat_ids' => $peminjaman->alat_ids
            ]);
        });
    }

    // PERUBAHAN SEDERHANA: Logika stok
    protected function updateStokAlat($oldStatus, $newStatus)
    {
        if (!$this->alat_ids || !is_array($this->alat_ids)) {
            return;
        }

        \Log::info("Update stok alat: $oldStatus -> $newStatus", [
            'alat_ids' => $this->alat_ids,
            'peminjaman_id' => $this->id
        ]);

        // 1. Jika status berubah ke 'dipinjam' (disetujui oleh petugas)
        if ($newStatus === 'dipinjam') {
            // Kurangi stok saat alat DISETUJUI untuk dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->decrement('stok');
            }
            
            \Log::info("Stok dikurangi untuk peminjaman #{$this->id}");
        }
        
        // 2. Jika status berubah ke 'selesai' (petugas konfirmasi pengembalian)
        elseif ($newStatus === 'selesai') {
            // Tambah stok kembali saat peminjaman SELESAI
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
            
            // Set tanggal dikembalikan
            $this->attributes['tanggal_dikembalikan'] = now();
            $this->attributes['waktu_pengembalian'] = now();
            
            \Log::info("Stok ditambahkan, peminjaman selesai #{$this->id}");
        }
        
        // 3. Jika status DITOLAK dari 'menunggu_peminjaman'
        elseif ($oldStatus === 'menunggu_peminjaman' && $newStatus === 'ditolak') {
            // Tidak ada perubahan stok karena belum pernah dikurangi
            \Log::info("Peminjaman ditolak, tidak ada perubahan stok #{$this->id}");
        }
        
        // 4. Jika status dibatalkan dari 'dipinjam' ke status lain (kecuali 'selesai')
        elseif ($oldStatus === 'dipinjam' && $newStatus !== 'selesai') {
            // Kembalikan stok karena dibatalkan setelah dipinjam
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
            \Log::info("Peminjaman dibatalkan, stok dikembalikan #{$this->id}");
        }
        
        // 5. Jika status ditegur
        elseif ($newStatus === 'ditegur') {
            \Log::info("Peminjaman ditegur #{$this->id}");
        }
    }

    // Relasi dan method lainnya tetap sama...
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

    public function getFotoBuktiUrlAttribute()
    {
        if ($this->foto_bukti) {
            return asset('storage/' . $this->foto_bukti);
        }
        return null;
    }

    public function isMenungguPeminjaman()
    {
        return $this->status === 'menunggu_peminjaman';
    }
}