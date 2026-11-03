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
        Schema::create('surat_persetujuan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat'); // SPB, SPP, SPK, SPA
            $table->unsignedBigInteger('request_id'); // ID dari request yang terkait
            $table->string('nomor_surat')->nullable(); // Nomor surat yang diisi admin
            $table->string('file_path'); // Path file surat persetujuan
            $table->string('file_name'); // Nama file asli
            $table->string('file_size')->nullable(); // Ukuran file
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->default('Menunggu');
            $table->unsignedBigInteger('uploaded_by'); // Admin yang upload
            $table->timestamps();
            
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_persetujuan');
    }
};
