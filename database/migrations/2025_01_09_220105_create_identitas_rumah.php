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
        Schema::create('identitas_rumah', function (Blueprint $table) {
            $table->uuid('id_rumah')->primary();
            $table->uuid('id_kk')->nullable();
            $table->string('alamat_rumah');
            $table->enum('tipe_lantai', ['keramik', 'ubin', 'kayu', 'tanah', 'lainnya']);
            $table->integer('jumlah_kamar_tidur');
            $table->integer('jumlah_kamar_mandi');
            $table->enum('atap', ['genteng', 'asbes', 'seng', 'jerami', 'lainnya']);
            $table->string('barcode')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas_rumah');
    }
};
