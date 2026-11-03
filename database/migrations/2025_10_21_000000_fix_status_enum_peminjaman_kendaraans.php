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
        // For SQLite, we need to recreate the table to change enum
        if (DB::getDriverName() === 'sqlite') {
            // Get all data first
            $records = DB::table('peminjaman_kendaraans')->get();
            
            // Drop and recreate table with correct schema
            Schema::dropIfExists('peminjaman_kendaraans_backup');
            Schema::rename('peminjaman_kendaraans', 'peminjaman_kendaraans_backup');
            
            Schema::create('peminjaman_kendaraans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('nama_kendaraan');
                $table->string('kategori'); // Changed from enum to string
                $table->string('merek');
                $table->integer('jumlah');
                $table->text('deskripsi_peruntukan');
                $table->string('status')->default('Menunggu'); // Changed from enum to string
                $table->text('komentar')->nullable();
                $table->timestamp('tanggal_peminjaman')->nullable();
                $table->timestamp('tanggal_pengembalian')->nullable();
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
                $table->timestamp('diteruskan_ke_kasubag_date')->nullable();
                $table->unsignedBigInteger('approved_by_kasubag_id')->nullable();
                $table->timestamp('approved_by_kasubag_date')->nullable();
                $table->timestamps();
                
                // Foreign keys
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
            });
            
            // Restore data
            foreach ($records as $record) {
                DB::table('peminjaman_kendaraans')->insert((array) $record);
            }
            
            // Drop backup table
            Schema::dropIfExists('peminjaman_kendaraans_backup');
            
            echo "Successfully migrated peminjaman_kendaraans for SQLite\n";
        } else {
            // For MySQL/PostgreSQL
            try {
                DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'Menunggu'");
                DB::statement("ALTER TABLE peminjaman_kendaraans MODIFY COLUMN kategori VARCHAR(255) NOT NULL");
            } catch (\Exception $e) {
                echo "Note: Failed to modify columns: " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert
    }
};

