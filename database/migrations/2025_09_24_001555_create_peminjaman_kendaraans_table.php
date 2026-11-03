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
        Schema::create('peminjaman_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_kendaraan');
            $table->enum('kategori', ['roda dua', 'roda empat']);
            $table->string('merek');
            $table->integer('jumlah');
            $table->text('deskripsi_peruntukan');
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->default('Menunggu');
            $table->text('komentar')->nullable();
            $table->timestamp('tanggal_peminjaman')->nullable();
            $table->timestamp('tanggal_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_kendaraans');
    }
};
