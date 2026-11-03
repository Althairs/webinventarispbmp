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
        // Pastikan stok diisi dengan nilai jumlah untuk data yang sudah ada
        DB::statement("UPDATE persediaan SET stok = COALESCE(jumlah, 0) WHERE stok IS NULL OR stok = 0");
        
        // Pastikan jumlah dan stok selalu sinkron untuk data baru
        // Kita akan handle ini di model layer
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena kita hanya update data
    }
};
