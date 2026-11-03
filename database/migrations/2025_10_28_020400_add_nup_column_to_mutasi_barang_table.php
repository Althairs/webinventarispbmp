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
        // Check if column already exists (it was added in create_mutasi_barang_table)
        $columnExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'mutasi_barang'
            AND COLUMN_NAME = 'nup'
        ");

        if ($columnExists[0]->count == 0) {
            Schema::table('mutasi_barang', function (Blueprint $table) {
                $table->string('nup', 50)->nullable()->after('nama_barang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            $table->dropColumn('nup');
        });
    }
};
