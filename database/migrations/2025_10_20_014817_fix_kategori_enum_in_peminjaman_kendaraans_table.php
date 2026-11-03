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
        // Change kategori column from enum to string to accept any category value
        try {
            DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN kategori VARCHAR(255) NOT NULL");
            echo "Fixed peminjaman_kendaraans kategori column - changed from enum to varchar\n";
        } catch (Exception $e) {
            echo "Error fixing peminjaman_kendaraans kategori: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN kategori ENUM('roda dua', 'roda empat') NOT NULL");
    }
};