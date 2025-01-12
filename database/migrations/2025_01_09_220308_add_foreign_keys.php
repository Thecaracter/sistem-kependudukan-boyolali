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
        // Pertama tambahkan foreign key untuk Kartu Keluarga ke Penduduk (kepala keluarga)
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->foreign('kepala_keluarga_id')
                ->references('id_penduduk')
                ->on('penduduk')
                ->onDelete('set null')  // Jika penduduk dihapus, KK tetap ada tapi kepala keluarga jadi null
                ->onUpdate('cascade');
        });

        // Kedua tambahkan foreign key untuk Penduduk ke KK
        Schema::table('penduduk', function (Blueprint $table) {
            $table->foreign('id_kk')
                ->references('id_kk')
                ->on('kartu_keluarga')
                ->onDelete('cascade')  // Jika KK dihapus, semua anggota keluarga terhapus
                ->onUpdate('cascade');
        });

        // Ketiga tambahkan foreign key untuk Verifikasi ke KK
        Schema::table('verifikasi_penduduk', function (Blueprint $table) {
            $table->foreign('id_kk')
                ->references('id_kk')
                ->on('kartu_keluarga')
                ->onDelete('cascade')   // Hapus verifikasi jika KK dihapus
                ->onUpdate('cascade');
        });

        // Keempat tambahkan foreign key untuk IdentitasRumah ke KK
        Schema::table('identitas_rumah', function (Blueprint $table) {
            $table->foreign('id_kk')
                ->references('id_kk')
                ->on('kartu_keluarga')
                ->onDelete('set null')  // Kalau KK dihapus, rumah tetap ada tapi id_kk jadi null
                ->onUpdate('cascade');
        });

        // Terakhir tambahkan foreign key untuk KK ke IdentitasRumah
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->foreign('id_rumah')
                ->references('id_rumah')
                ->on('identitas_rumah')
                ->onDelete('set null')  // Kalau rumah dihapus, KK tetap ada tapi id_rumah jadi null
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dengan urutan terbalik
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->dropForeign(['id_rumah']);
        });

        Schema::table('identitas_rumah', function (Blueprint $table) {
            $table->dropForeign(['id_kk']);
        });

        Schema::table('verifikasi_penduduk', function (Blueprint $table) {
            $table->dropForeign(['id_kk']);
        });

        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropForeign(['id_kk']);
        });

        Schema::table('kartu_keluarga', function (Blueprint $table) {
            $table->dropForeign(['kepala_keluarga_id']);
        });
    }
};