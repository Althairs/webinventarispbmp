<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sinkronisasi stok dengan jumlah untuk data existing
     */
    public function up(): void
    {
        // Update stok = jumlah untuk semua record yang stok tidak sesuai
        DB::statement("
            UPDATE aset_tetap 
            SET stok = jumlah 
            WHERE stok IS NULL OR stok != jumlah OR stok < 0
        ");
        
        // Pastikan stok tidak negatif
        DB::statement("
            UPDATE aset_tetap 
            SET stok = 0 
            WHERE stok < 0
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set stok kembali ke 0 (karena tidak bisa rollback ke nilai sebelumnya)
        DB::statement("
            UPDATE aset_tetap 
            SET stok = 0
        ");
    }
};
