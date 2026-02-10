<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // migration file
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Ubah email menjadi nullable
        $table->string('email')->nullable()->change();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('email')->nullable(false)->change();
    });
}
};
