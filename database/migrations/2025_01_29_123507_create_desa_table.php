<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_desa')->nullable();

            $table->foreign('id_desa')
                ->references('id')
                ->on('desa')
                ->onDelete('set null');
        });

        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->unsignedBigInteger('id_desa');

            $table->foreign('id_desa')
                ->references('id')
                ->on('desa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};