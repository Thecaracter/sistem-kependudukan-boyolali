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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->uuid('id_penduduk')->primary();
            $table->string('nama');
            $table->string('nik', 16)->unique();
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->enum('status_keluarga', [
                'kepala_keluarga',
                'istri',
                'anak',
                'menantu',
                'cucu',
                'orangtua',
                'mertua',
                'famili_lain',
                'lainnya'
            ]);
            $table->enum('pendidikan', [
                'tidak_sekolah',
                'sd',
                'smp',
                'sma',
                'd1',
                'd2',
                'd3',
                's1',
                's2',
                's3'
            ]);
            $table->uuid('id_kk');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
