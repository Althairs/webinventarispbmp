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
        Schema::table('mutasi_barang', function (Blueprint $table) {
            // Pastikan semua kolom yang diperlukan ada
            if (!Schema::hasColumn('mutasi_barang', 'nup')) {
                $table->string('nup', 50)->nullable()->after('nama_barang');
            }
            if (!Schema::hasColumn('mutasi_barang', 'lokasi_awal')) {
                $table->string('lokasi_awal', 100)->nullable()->after('nup');
            }
            if (!Schema::hasColumn('mutasi_barang', 'lokasi_akhir')) {
                $table->string('lokasi_akhir', 100)->nullable()->after('lokasi_awal');
            }
            if (!Schema::hasColumn('mutasi_barang', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('tanggal_mutasi');
            }
            if (!Schema::hasColumn('mutasi_barang', 'aset_tetap_id')) {
                $table->foreignId('aset_tetap_id')->nullable()->constrained('aset_tetap')->onDelete('cascade')->after('keterangan');
            }
            if (!Schema::hasColumn('mutasi_barang', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('aset_tetap_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan oleh migration ini
            $columnsToDrop = ['nup', 'lokasi_awal', 'lokasi_akhir', 'keterangan', 'aset_tetap_id', 'user_id'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('mutasi_barang', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
