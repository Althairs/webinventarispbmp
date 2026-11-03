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
        // Update role_request untuk user dengan role super_admin yang masih memiliki role_request 'admin'
        DB::table('users')
            ->where('role', 'super_admin')
            ->where('role_request', 'admin')
            ->update(['role_request' => 'super_admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
