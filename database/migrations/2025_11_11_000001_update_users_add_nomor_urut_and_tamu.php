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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nomor_urut')) {
                $table->unsignedInteger('nomor_urut')->nullable()->after('signature_image_path');
            }
        });

        // Safely alter enum columns for MySQL to include 'tamu'
        $connection = config('database.default');
        $driver = config("database.connections.$connection.driver");

        if ($driver === 'mysql') {
            // Pastikan semua role yang sudah ada tetap tersedia.
            // Jangan hanya menambahkan 'tamu', karena migration ini berjalan
            // setelah role 'kasubag' dan 'kepala_bpmp' ditambahkan.
            DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin','admin','user','kasubag','kepala_bpmp','tamu') NOT NULL DEFAULT 'user'");
            // role_request juga harus mendukung seluruh role tersebut
            DB::statement("ALTER TABLE users MODIFY role_request ENUM('super_admin','admin','user','kasubag','kepala_bpmp','tamu') NOT NULL DEFAULT 'user'");
        }
        // For SQLite/PostgreSQL we don't need to change anything because enum is stored as string or handled differently.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum changes on MySQL
        $connection = config('database.default');
        $driver = config("database.connections.$connection.driver");

        if ($driver === 'mysql') {
            // Kembalikan enum tanpa menghilangkan role yang sudah dipakai.
            DB::statement("ALTER TABLE users MODIFY role ENUM('super_admin','admin','user','kasubag','kepala_bpmp','tamu') NOT NULL DEFAULT 'user'");
            DB::statement("ALTER TABLE users MODIFY role_request ENUM('super_admin','admin','user','kasubag','kepala_bpmp','tamu') NOT NULL DEFAULT 'user'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nomor_urut')) {
                $table->dropColumn('nomor_urut');
            }
        });
    }
};


