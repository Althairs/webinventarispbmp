<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns already exist (they were added in create_mutasi_barang_table)
        $lokasiAwalExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'mutasi_barang'
            AND COLUMN_NAME = 'lokasi_awal'
        ");

        if ($lokasiAwalExists[0]->count == 0) {
            Schema::table('mutasi_barang', function (Blueprint $table) {
                $table->string('lokasi_awal', 100)->nullable()->after('nup');
                $table->string('lokasi_akhir', 100)->nullable()->after('lokasi_awal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            $table->dropColumn(['lokasi_awal', 'lokasi_akhir']);
        });
    }
};
