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
        Schema::create('request_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('kode_barang', 50);
            $table->string('kategori', 50);
            $table->string('marek', 50);
            $table->string('ruangan', 50);
            $table->integer('jumlah');
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->default('Menunggu');
            $table->date('request_date');
            $table->unsignedBigInteger('approved_by_admin_id')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Kunci Asing
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Foreign key untuk kode_barang dihapus karena product_models tidak ada
            // Akan ditambahkan kembali nanti jika diperlukan setelah tabel yang tepat dibuat
            
            $table->foreign('approved_by_admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PERBAIKAN: Mengubah 'product_models' menjadi 'request_models'
        Schema::dropIfExists('request_models');
    }
};
