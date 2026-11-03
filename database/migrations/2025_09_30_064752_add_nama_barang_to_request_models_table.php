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
            $table->string('nama_barang')->after('user_id');
            $table->renameColumn('marek', 'merek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_models', function (Blueprint $table) {
            $table->dropColumn('nama_barang');
            $table->renameColumn('merek', 'marek');
        });
    }
};
