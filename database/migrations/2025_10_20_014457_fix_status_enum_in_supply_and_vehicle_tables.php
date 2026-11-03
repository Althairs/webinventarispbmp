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
        // Fix permintaan_persediaans table
        DB::statement("ALTER TABLE permintaan_persediaans MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
        
        // Fix peminjaman_kendaraans table
        DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE permintaan_persediaans MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
        DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
    }
};