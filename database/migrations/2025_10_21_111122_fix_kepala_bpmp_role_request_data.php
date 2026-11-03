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
        // Update role_request untuk user dengan role kepala_bpmp yang masih memiliki role_request 'user' atau 'admin'
        DB::table('users')
            ->where('role', 'kepala_bpmp')
            ->whereIn('role_request', ['user', 'admin'])
            ->update(['role_request' => 'kepala_bpmp']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
