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
        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_keluar_persediaan', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar_persediaan', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keluar_persediaan', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

