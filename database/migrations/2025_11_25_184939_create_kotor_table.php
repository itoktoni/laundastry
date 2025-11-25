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
        Schema::create('kotor', function (Blueprint $table) {
            $table->bigInteger('kotor_id', true);
            $table->string('kotor_code')->nullable();
            $table->string('kotor_code_customer', 20)->nullable();
            $table->bigInteger('kotor_id_jenis')->nullable();
            $table->integer('kotor_qty')->nullable();
            $table->date('kotor_tanggal')->nullable();
            $table->dateTime('kotor_created_at')->nullable();
            $table->dateTime('kotor_updated_at')->nullable();
            $table->dateTime('kotor_deleted_at')->nullable();
            $table->integer('kotor_created_by')->nullable();
            $table->integer('kotor_updated_by')->nullable();
            $table->integer('kotor_deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kotor');
    }
};
