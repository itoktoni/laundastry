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
        Schema::create('jenis', function (Blueprint $table) {
            $table->bigInteger('jenis_id', true);
            $table->string('jenis_nama')->nullable();
            $table->string('jenis_code_customer')->nullable();
            $table->integer('jenis_harga')->nullable();
            $table->integer('jenis_fee')->nullable();
            $table->integer('jenis_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis');
    }
};
