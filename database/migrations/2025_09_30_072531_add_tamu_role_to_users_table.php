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
        Schema::table('users', function (Blueprint $table) {
            // Update role enum to include 'tamu'
            $table->enum('role', ['super_admin', 'admin', 'user', 'tamu'])->default('user')->change();
            
            // Update role_request enum to include 'tamu'
            $table->enum('role_request', ['admin', 'user', 'tamu'])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert role enum
            $table->enum('role', ['super_admin', 'admin', 'user'])->default('user')->change();
            
            // Revert role_request enum
            $table->enum('role_request', ['admin', 'user'])->default('user')->change();
        });
    }
};
