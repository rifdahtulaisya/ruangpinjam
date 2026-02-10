<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus jurusan dan kelas terpisah
            $table->dropColumn(['jurusan', 'kelas']);
            // Tambahkan kelas_jurusan sebagai satu field
            $table->string('kelas_jurusan')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelas_jurusan');
            $table->string('jurusan')->nullable();
            $table->string('kelas')->nullable();
        });
    }
};