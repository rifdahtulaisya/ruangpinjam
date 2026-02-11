<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisPengembalianAndTeguranFieldsToPeminjamanTable extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('jenis_pengembalian', ['manual', 'mandiri'])->nullable()->after('foto_bukti');
            $table->timestamp('teguran_dikirim_at')->nullable()->after('jenis_pengembalian');
            $table->foreignId('petugas_id_teguran')->nullable()->after('teguran_dikirim_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['petugas_id_teguran']);
            $table->dropColumn(['jenis_pengembalian', 'teguran_dikirim_at', 'petugas_id_teguran']);
        });
    }
}