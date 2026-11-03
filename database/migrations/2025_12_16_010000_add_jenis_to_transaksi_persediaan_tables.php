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
        // Tambah kolom jenis ke transaksi_masuk_persediaan
        Schema::table('transaksi_masuk_persediaan', function (Blueprint $table) {
            $table->enum('jenis', ['habis_pakai', 'tidak_habis_pakai'])
                ->default('habis_pakai')
                ->after('nama_barang');
        });

        // Tambah kolom jenis ke transaksi_keluar_persediaan
        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            $table->enum('jenis', ['habis_pakai', 'tidak_habis_pakai'])
                ->default('habis_pakai')
                ->after('nama_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk_persediaan', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });

        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
