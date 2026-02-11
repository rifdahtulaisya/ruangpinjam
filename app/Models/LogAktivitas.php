<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'role',
        'aktivitas',
        'modul',
        'created_at' // tambahkan ini
    ];

    // Timestamps tetap false karena kita manage sendiri created_at
    public $timestamps = false;
    
    // Cast created_at ke Carbon
    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}