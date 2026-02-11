<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('peminjaman', 'tanggal_dikembalikan')) {
                $table->dateTime('tanggal_dikembalikan')->nullable()->after('tanggal_pengembalian');
            }
            
            if (!Schema::hasColumn('peminjaman', 'waktu_pengembalian')) {
                $table->time('waktu_pengembalian')->nullable()->after('tanggal_dikembalikan');
            }
            
            if (!Schema::hasColumn('peminjaman', 'metode_pengembalian')) {
                $table->string('metode_pengembalian')->nullable()->after('waktu_pengembalian');
            }
            
            if (!Schema::hasColumn('peminjaman', 'foto_bukti')) {
                $table->string('foto_bukti')->nullable()->after('metode_pengembalian');
            }
            
            // Ubah tipe kolom status agar bisa menampung nilai yang lebih panjang
            $table->string('status', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropColumn(['tanggal_dikembalikan', 'waktu_pengembalian', 'metode_pengembalian', 'foto_bukti']);
            // Kembalikan tipe kolom status
            $table->string('status', 20)->change();
        });
    }
};