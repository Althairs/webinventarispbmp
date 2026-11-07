<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin','user','kasubag','kepala_bpmp','tamu') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE `users` MODIFY `role_request` ENUM('admin','user','tamu') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin','user','kasubag') NOT NULL DEFAULT 'user'");
        DB::statement("ALTER TABLE `users` MODIFY `role_request` ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
