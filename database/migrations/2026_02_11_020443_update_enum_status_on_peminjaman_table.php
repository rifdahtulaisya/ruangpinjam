<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah dulu data lama 'menunggu' → 'menunggu_peminjaman'
        DB::table('peminjaman')
            ->where('status', 'menunggu')
            ->update(['status' => 'menunggu_peminjaman']);

        // 2. Ubah ENUM kolom status
        DB::statement("
            ALTER TABLE peminjaman 
            MODIFY COLUMN status ENUM(
                'menunggu_peminjaman',
                'menunggu_pengembalian',
                'dipinjam',
                'selesai',
                'ditolak',
                'ditegur'
            ) NOT NULL DEFAULT 'menunggu_peminjaman'
        ");
    }

    public function down(): void
    {
        // rollback → kembalikan ke enum lama

        // ubah data dulu
        DB::table('peminjaman')
            ->where('status', 'menunggu_peminjaman')
            ->update(['status' => 'menunggu']);

        DB::table('peminjaman')
            ->where('status', 'menunggu_pengembalian')
            ->update(['status' => 'menunggu']);

        // ubah enum lama
        DB::statement("
            ALTER TABLE peminjaman 
            MODIFY COLUMN status ENUM(
                'menunggu',
                'ditolak',
                'dipinjam',
                'selesai',
                'ditegur'
            ) NOT NULL DEFAULT 'menunggu'
        ");
    }
};
