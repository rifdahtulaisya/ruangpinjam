<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Untuk MySQL, kita perlu pendekatan yang berbeda
        // 1. Cek versi MySQL dan gunakan approach yang sesuai
        
        // Pertama, ubah kolom menjadi VARCHAR sementara
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'menunggu'");
        
        // Update data yang menggunakan nilai lama ke nilai baru
        DB::table('peminjaman')
            ->whereIn('status', ['dikonfirmasi', 'dikembalikan', 'terlambat', 'hilang', 'rusak'])
            ->update(['status' => 'selesai']);
        
        // Ubah kembali ke ENUM dengan nilai baru
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('menunggu', 'ditolak', 'dipinjam', 'selesai', 'ditegur') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah kembali ke VARCHAR untuk mengubah ENUM
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'menunggu'");
        
        // Update data kembali ke nilai lama (opsional)
        DB::table('peminjaman')
            ->where('status', 'selesai')
            ->update(['status' => 'dikembalikan']);
        
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('menunggu', 'dikonfirmasi', 'ditolak', 'dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'rusak') NOT NULL DEFAULT 'menunggu'");
    }
};