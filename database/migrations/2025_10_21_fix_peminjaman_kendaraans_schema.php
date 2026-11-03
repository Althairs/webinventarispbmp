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
        // Check if table exists and add missing columns
        if (Schema::hasTable('peminjaman_kendaraans')) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('peminjaman_kendaraans', 'reviewed_by_admin_id')) {
                Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
                    $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                });
            }
            
            if (!Schema::hasColumn('peminjaman_kendaraans', 'diteruskan_ke_kasubag_date')) {
                Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
                    $table->timestamp('diteruskan_ke_kasubag_date')->nullable();
                });
            }
            
            if (!Schema::hasColumn('peminjaman_kendaraans', 'approved_by_kasubag_id')) {
                Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
                    $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                });
            }
            
            if (!Schema::hasColumn('peminjaman_kendaraans', 'approved_by_kasubag_date')) {
                Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
                    $table->timestamp('approved_by_kasubag_date')->nullable();
                });
            }
            
            // Update status enum to include new values
            try {
                DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status ENUM('Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected') NOT NULL DEFAULT 'Menunggu'");
            } catch (Exception $e) {
                // If SQLite doesn't support MODIFY, try alternative approach
                echo "Note: Status enum update skipped for SQLite compatibility\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('peminjaman_kendaraans')) {
            Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
                $table->dropColumn([
                    'reviewed_by_admin_id',
                    'diteruskan_ke_kasubag_date',
                    'approved_by_kasubag_id',
                    'approved_by_kasubag_date'
                ]);
            });
        }
    }
};
