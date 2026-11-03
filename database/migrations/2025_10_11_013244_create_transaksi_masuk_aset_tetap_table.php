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
        Schema::create('transaksi_masuk_aset_tetap', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->string('kategori', 50);
            $table->string('kode_barang', 50);
            $table->string('nama_barang', 100);
            $table->integer('jumlah_masuk');
            $table->decimal('harga', 15, 2);
            $table->foreignId('aset_tetap_id')->constrained('aset_tetap')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk_aset_tetap');
    }
};
