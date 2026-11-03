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
        // Check if column already exists (it was added in 2025_10_28_102344_add_surat_path_to_peminjaman_asets_table)
        $columnExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'peminjaman_asets'
            AND COLUMN_NAME = 'surat_path'
        ");

        if ($columnExists[0]->count == 0) {
            Schema::table('peminjaman_asets', function (Blueprint $table) {
                $table->string('surat_path')->nullable()->after('jenis');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_asets', function (Blueprint $table) {
            $table->dropColumn('surat_path');
        });
    }
};
