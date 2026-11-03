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
        // Force fix all status columns with proper enum values
        try {
            // Fix request_models table
            DB::statement("ALTER TABLE request_models MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
            echo "Fixed request_models status column\n";
        } catch (Exception $e) {
            echo "Error fixing request_models: " . $e->getMessage() . "\n";
        }

        try {
            // Fix permintaan_persediaans table
            DB::statement("ALTER TABLE permintaan_persediaans MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
            echo "Fixed permintaan_persediaans status column\n";
        } catch (Exception $e) {
            echo "Error fixing permintaan_persediaans: " . $e->getMessage() . "\n";
        }

        try {
            // Fix peminjaman_kendaraans table
            DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
            echo "Fixed peminjaman_kendaraans status column\n";
        } catch (Exception $e) {
            echo "Error fixing peminjaman_kendaraans: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE request_models MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
        DB::statement("ALTER TABLE permintaan_persediaans MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
        DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
    }
};