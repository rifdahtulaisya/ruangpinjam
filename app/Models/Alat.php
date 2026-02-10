<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Alat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alat';
    
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'foto',
        'kondisi',
        'stok',
        'lokasi'
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Accessor untuk foto URL
    public function getFotoUrlAttribute()
    {
        // Jika ada foto di database
        if (!empty($this->foto)) {
            // Cek apakah file benar-benar ada di storage
            if (Storage::disk('public')->exists($this->foto)) {
                // Menggunakan asset() untuk mendapatkan URL
                return Storage::url($this->foto);
            }
        }
        
        // Default image
        return asset('assets-admin/img/2.svg');
    }

    // Accessor untuk cek apakah ada foto
    public function getHasFotoAttribute()
    {
        return !empty($this->foto) && Storage::disk('public')->exists($this->foto);
    }
    
    // Helper untuk mendapatkan nama file saja
    public function getNamaFotoAttribute()
    {
        if (!empty($this->foto)) {
            return basename($this->foto);
        }
        return null;
    }
}