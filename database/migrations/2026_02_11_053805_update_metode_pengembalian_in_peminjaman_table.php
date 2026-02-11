<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Di migration baru
public function up()
{
    Schema::table('peminjaman', function (Blueprint $table) {
        // Ubah menjadi string yang lebih panjang
        $table->string('metode_pengembalian', 50)->nullable()->change();
        
        // Atau jika pakai enum, tambahkan nilai 'petugas'
        // $table->enum('metode_pengembalian', ['langsung', 'mandiri', 'petugas'])->nullable()->change();
    });
}

public function down()
{
    Schema::table('peminjaman', function (Blueprint $table) {
        // Kembalikan ke semula
        $table->string('metode_pengembalian', 20)->nullable()->change();
    });
}
};
