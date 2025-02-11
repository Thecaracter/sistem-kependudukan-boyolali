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
        Schema::create('verifikasi_penduduk', function (Blueprint $table) {
            $table->uuid('id_verifikasi')->primary();
            $table->uuid('id_kk');
            $table->enum('status', ['pending', 'verified', 'rejected']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_penduduk');
    }
};
