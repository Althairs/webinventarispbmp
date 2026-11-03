<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update data existing yang memiliki ruangan tapi tidak ada deskripsi_peruntukan
        DB::statement("
            UPDATE request_models 
            SET deskripsi_peruntukan = ruangan 
            WHERE deskripsi_peruntukan IS NULL 
            AND ruangan IS NOT NULL 
            AND ruangan != ''
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini hanya update data
    }
};
