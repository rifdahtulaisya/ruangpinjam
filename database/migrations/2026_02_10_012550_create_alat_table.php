<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('nama_alat', 100);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'perlu_perbaikan'])->default('baik');
            $table->integer('stok')->default(0);
            $table->string('lokasi', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};