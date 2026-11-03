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
        Schema::table('transaksi_masuk_persediaan', function (Blueprint $table) {
            $table->string('kode_kategori', 50)->nullable()->after('tanggal_input');
        });

        Schema::table('transaksi_masuk_aset_tetap', function (Blueprint $table) {
            $table->string('kode_kategori', 50)->nullable()->after('tanggal_input');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk_persediaan', function (Blueprint $table) {
            $table->dropColumn('kode_kategori');
        });

        Schema::table('transaksi_masuk_aset_tetap', function (Blueprint $table) {
            $table->dropColumn('kode_kategori');
        });
    }
};
