<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if foreign key exists before trying to drop it
        $foreignKeyExists = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'request_models'
            AND CONSTRAINT_NAME = 'request_models_kode_barang_foreign'
        ");

        if ($foreignKeyExists[0]->count > 0) {
            Schema::table('request_models', function (Blueprint $table) {
                $table->dropForeign(['kode_barang']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_models', function (Blueprint $table) {
            $table->foreign('kode_barang')->references('kode_barang')->on('product_models')->onDelete('cascade');
        });
    }
};
