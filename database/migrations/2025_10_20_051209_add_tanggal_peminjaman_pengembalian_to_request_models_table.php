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
        Schema::table('request_models', function (Blueprint $table) {
            $table->timestamp('tanggal_peminjaman')->nullable()->after('request_date');
            $table->timestamp('tanggal_pengembalian')->nullable()->after('tanggal_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_models', function (Blueprint $table) {
            $table->dropColumn(['tanggal_peminjaman', 'tanggal_pengembalian']);
        });
    }
};
