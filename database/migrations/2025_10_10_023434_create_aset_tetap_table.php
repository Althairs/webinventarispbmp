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
        Schema::create('aset_tetap', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50);
            $table->string('nup', 50);
            $table->string('nama_barang', 100);
            $table->string('merek', 100);
            $table->date('tanggal_peroleh');
            $table->decimal('nilai_perolehan', 15, 2);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->string('lokasi', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_tetap');
    }
};
