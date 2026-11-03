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
        Schema::table('persediaan', function (Blueprint $table) {
            // Menambah kolom enum `jenis` tanpa mengganggu data/fitur lain
            $table->enum('jenis', ['habis_pakai', 'tidak_habis_pakai'])
                ->default('habis_pakai')
                ->after('nama_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
