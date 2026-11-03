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
            $table->integer('stok')->default(0)->after('harga');
        });

        Schema::table('aset_tetap', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persediaan', function (Blueprint $table) {
            $table->dropColumn('stok');
        });

        Schema::table('aset_tetap', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};
