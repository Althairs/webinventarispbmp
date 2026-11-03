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
        Schema::table('mutasi_barang', function (Blueprint $table) {
            if (!Schema::hasColumn('mutasi_barang', 'dari_ruangan')) {
                $table->string('dari_ruangan', 100)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('mutasi_barang', 'ke_ruangan')) {
                $table->string('ke_ruangan', 100)->nullable()->after('dari_ruangan');
            }
            if (!Schema::hasColumn('mutasi_barang', 'jumlah')) {
                $table->integer('jumlah')->default(1)->after('ke_ruangan');
            }
            if (!Schema::hasColumn('mutasi_barang', 'alasan')) {
                $table->text('alasan')->nullable()->after('jumlah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            if (Schema::hasColumn('mutasi_barang', 'dari_ruangan')) {
                $table->dropColumn('dari_ruangan');
            }
            if (Schema::hasColumn('mutasi_barang', 'ke_ruangan')) {
                $table->dropColumn('ke_ruangan');
            }
            if (Schema::hasColumn('mutasi_barang', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
            if (Schema::hasColumn('mutasi_barang', 'alasan')) {
                $table->dropColumn('alasan');
            }
        });
    }
};

