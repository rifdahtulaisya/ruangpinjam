<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update status 'menunggu' menjadi 'menunggu_peminjaman' untuk semua data
        DB::table('peminjaman')
            ->where('status', 'menunggu')
            ->update(['status' => 'menunggu_peminjaman']);
            
        // Jika ada data yang seharusnya 'menunggu_pengembalian', 
        // kita akan handle dengan script terpisah atau di controller
    }

    public function down()
    {
        // Kembalikan ke status 'menunggu'
        DB::table('peminjaman')
            ->whereIn('status', ['menunggu_peminjaman', 'menunggu_pengembalian'])
            ->update(['status' => 'menunggu']);
    }
};