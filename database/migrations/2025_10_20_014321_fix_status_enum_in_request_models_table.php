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
        // First, update any existing 'Menunggu' values to ensure they're valid
        DB::table('request_models')
            ->where('status', 'Menunggu')
            ->update(['status' => 'Menunggu']);

        // Then modify the enum to include all valid values
        DB::statement("ALTER TABLE request_models MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE request_models MODIFY COLUMN status ENUM('Menunggu', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
    }
};