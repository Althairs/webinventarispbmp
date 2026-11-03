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
        Schema::table('aset_tetap', function (Blueprint $table) {
            $table->string('kode_kategori', 50)->nullable()->after('id');
            $table->string('kategori', 100)->nullable()->after('kode_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_tetap', function (Blueprint $table) {
            $table->dropColumn(['kode_kategori', 'kategori']);
        });
    }
};
