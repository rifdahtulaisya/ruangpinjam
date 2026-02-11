<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data alat yang dipinjam (simpan sebagai JSON)
            $table->json('alat_ids'); // Array ID alat [1, 3, 5]
            $table->text('nama_alat'); // String "Bor Listrik, Gergaji, Palu"
            
            // Data peminjaman
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->text('keterangan')->nullable();
            
            // Status peminjaman
            $table->enum('status', [
                'menunggu',      // Menunggu konfirmasi petugas
                'dikonfirmasi',  // Disetujui petugas
                'ditolak',       // Ditolak petugas
                'dipinjam',      // Sudah diambil peminjam
                'dikembalikan',  // Sudah dikembalikan
                'terlambat',     // Telat mengembalikan
                'hilang',        // Alat hilang
                'rusak'          // Alat rusak saat dipinjam
            ])->default('menunggu');
            
            // Data pengembalian
            $table->date('tanggal_pengembalian_aktual')->nullable();
            $table->time('waktu_pengembalian')->nullable();
            $table->enum('metode_pengembalian', [
                'langsung',
                'mandiri'
            ])->nullable();
            
            // Bukti pengembalian
            $table->string('foto_bukti')->nullable();
            
            $table->timestamps();
            
            // Index untuk pencarian cepat
            $table->index('status');
            $table->index('tanggal_peminjaman');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
};