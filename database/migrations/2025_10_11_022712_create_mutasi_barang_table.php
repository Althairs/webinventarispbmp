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
        Schema::create('mutasi_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50);
            $table->string('nama_barang', 100);
            $table->string('nup', 50);
            $table->string('lokasi_awal', 100);
            $table->string('lokasi_akhir', 100);
            $table->date('tanggal_mutasi');
            $table->text('keterangan')->nullable();
            $table->foreignId('aset_tetap_id')->constrained('aset_tetap')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_barang');
    }
};
