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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User yang menerima notifikasi
            $table->string('jenis_notifikasi'); // 'surat_persetujuan', 'approval', dll
            $table->string('judul');
            $table->text('pesan');
            $table->string('jenis_surat')->nullable(); // SPA, SPB, SPP, SPK
            $table->unsignedBigInteger('request_id')->nullable(); // ID request terkait
            $table->unsignedBigInteger('surat_persetujuan_id')->nullable(); // ID surat persetujuan
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('surat_persetujuan_id')->references('id')->on('surat_persetujuan')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
