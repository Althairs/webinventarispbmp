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
        Schema::table('transaksi_keluar_aset_tetap', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_keluar_aset_tetap', 'nilai_perolehan')) {
                $table->decimal('nilai_perolehan', 15, 2)->default(0)->after('jumlah_keluar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar_aset_tetap', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keluar_aset_tetap', 'nilai_perolehan')) {
                $table->dropColumn('nilai_perolehan');
            }
        });
    }
};

