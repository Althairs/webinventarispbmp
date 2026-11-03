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
            $table->integer('nomor_urut')->nullable()->after('id');
        });
        
        // Update nomor_urut untuk data yang sudah ada
        $users = \App\Models\User::orderBy('id')->get();
        foreach ($users as $index => $user) {
            $user->update(['nomor_urut' => $index + 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nomor_urut');
        });
    }
};
