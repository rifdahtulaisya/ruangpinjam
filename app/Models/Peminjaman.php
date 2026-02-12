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
        'foto_bukti',
        'waktu_pengembalian',
        'jenis_pengembalian',
        'teguran_dikirim_at',
        'petugas_id_teguran',
    ];

    protected $casts = [
        'alat_ids' => 'array',
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_dikembalikan' => 'datetime',
        'waktu_pengembalian' => 'datetime',
        'teguran_dikirim_at' => 'datetime',
    ];

    public static $statuses = [
        'menunggu_peminjaman',
        'dipinjam',
        'selesai',
        'ditolak',
        'ditegur',
        'menunggu_verifikasi'
    ];

    public function setStatusAttribute($value)
    {
        $oldStatus = $this->attributes['status'] ?? null;
        
        if (!in_array($value, self::$statuses)) {
            throw new \InvalidArgumentException("Status tidak valid.");
        }
        
        $this->attributes['status'] = $value;
        
        if ($oldStatus !== $value) {
            $this->updateStokAlat($oldStatus, $value);
            
            // CEK APAKAH STATUS BERUBAH KE SELESAI
            if ($value === 'selesai') {
                $this->handlePengembalianSelesai();
            }
        }
    }

   

    // App\Models\Peminjaman.php

protected function handlePengembalianSelesai()
{
    if (!$this->tanggal_dikembalikan || !$this->tanggal_pengembalian) {
        return;
    }
    
    $jatuhTempo = \Carbon\Carbon::parse($this->tanggal_pengembalian);
    $dikembalikan = \Carbon\Carbon::parse($this->tanggal_dikembalikan);
    
    // ✅ CEK APAKAH INI PEMINJAMAN YANG TELAT
    if ($dikembalikan->greaterThan($jatuhTempo)) {
        $hariTelat = $jatuhTempo->diffInDays($dikembalikan);
        
        // CATAT KETERANGAN TELAT
        $this->keterangan = ($this->keterangan ? $this->keterangan . ' | ' : '') 
            . "DIKEMBALIKAN TELAT {$hariTelat} HARI";
        $this->saveQuietly();
    }
    
    // ✅ CEK APAKAH USER DIBLOKIR
    $user = $this->user;
    if ($user && $user->is_blocked) {
        // CEK APAKAH MASIH ADA PEMINJAMAN TELAT LAINNYA YANG STATUSNYA DIPINJAM
        $masihAdaPeminjamanTelat = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_pengembalian', '<', \Carbon\Carbon::today())
            ->exists();
        
        // JIKA SUDAH TIDAK ADA PEMINJAMAN TELAT, UNBLOCK USER
        if (!$masihAdaPeminjamanTelat) {
            $user->unblock("Akun dipulihkan otomatis - Semua peminjaman telat telah dikembalikan dan dikonfirmasi");
            
            // Log pemulihan
            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'role' => $user->role,
                'aktivitas' => 'Akun dipulihkan otomatis - Semua peminjaman telat telah dikonfirmasi',
                'modul' => 'peminjaman',
                'data' => json_encode([
                    'peminjaman_id' => $this->id,
                    'hari_telat' => $hariTelat ?? 0,
                    'dikonfirmasi_at' => now()->toDateTimeString()
                ])
            ]);
        }
    }
}

    protected function updateStokAlat($oldStatus, $newStatus)
    {
        if (!$this->alat_ids || !is_array($this->alat_ids)) {
            return;
        }

        if ($newStatus === 'dipinjam') {
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->decrement('stok');
            }
        }
        elseif ($newStatus === 'selesai') {
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
            $this->attributes['tanggal_dikembalikan'] = now();
            $this->attributes['waktu_pengembalian'] = now();
        }
        elseif ($oldStatus === 'dipinjam' && $newStatus !== 'selesai' && $newStatus !== 'menunggu_verifikasi') {
            foreach ($this->alat_ids as $alatId) {
                Alat::where('id', $alatId)->increment('stok');
            }
        }
    }

    // Relasi dan method lainnya...
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function petugasTeguran()
    {
        return $this->belongsTo(User::class, 'petugas_id_teguran');
    }

    /**
     * CEK APAKAH PEMINJAMAN INI TELAT
     */
    public function isTelat()
    {
        if ($this->status === 'selesai' && $this->tanggal_dikembalikan) {
            return \Carbon\Carbon::parse($this->tanggal_dikembalikan)
                ->greaterThan(\Carbon\Carbon::parse($this->tanggal_pengembalian));
        }
        return false;
    }

    /**
     * HITUNG HARI TELAT
     */
    public function getHariTelat()
    {
        if (!$this->isTelat()) {
            return 0;
        }
        
        $jatuhTempo = \Carbon\Carbon::parse($this->tanggal_pengembalian);
        $dikembalikan = \Carbon\Carbon::parse($this->tanggal_dikembalikan);
        
        return $jatuhTempo->diffInDays($dikembalikan);
    }
    
    // Method lainnya...
    public function getAlatListAttribute()
    {
        if (!$this->alat_ids || !is_array($this->alat_ids)) {
            return collect();
        }
        return Alat::whereIn('id', $this->alat_ids)->get();
    }
    
    public function isPengembalianMandiri()
    {
        return $this->jenis_pengembalian === 'mandiri';
    }
    
    public function hasTeguran()
    {
        return $this->status === 'ditegur' && $this->keterangan && strpos($this->keterangan, 'TEGURAN:') === 0;
    }
    
    public function getTeksTeguran()
    {
        if ($this->hasTeguran()) {
            return str_replace('TEGURAN: ', '', $this->keterangan);
        }
        return null;
    }

    // Save tanpa trigger event
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    
}