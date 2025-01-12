<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->uuid('id_kk')->primary();
            $table->string('nomor_kk', 16)->unique();
            $table->uuid('kepala_keluarga_id')->nullable();
            $table->date('tanggal_pembuatan');
            $table->uuid('id_rumah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga_t');
    }
};
