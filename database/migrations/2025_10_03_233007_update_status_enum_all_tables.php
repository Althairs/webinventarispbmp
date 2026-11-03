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
        // Update request_models table
        Schema::table('request_models', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected'])->change();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable()->after('approved_by_admin_id');
            $table->timestamp('diteruskan_ke_kasubag_date')->nullable()->after('reviewed_by_admin_id');
            $table->unsignedBigInteger('approved_by_kasubag_id')->nullable()->after('diteruskan_ke_kasubag_date');
            $table->timestamp('approved_by_kasubag_date')->nullable()->after('approved_by_kasubag_id');
            
            // Foreign keys
            $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update permintaan_persediaans table
        Schema::table('permintaan_persediaans', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected'])->change();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('diteruskan_ke_kasubag_date')->nullable()->after('reviewed_by_admin_id');
            $table->unsignedBigInteger('approved_by_kasubag_id')->nullable()->after('diteruskan_ke_kasubag_date');
            $table->timestamp('confirmed_by_kasubag_date')->nullable()->after('approved_by_kasubag_id');
            
            // Foreign keys
            $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update peminjaman_kendaraans table
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected'])->change();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('diteruskan_ke_kasubag_date')->nullable()->after('reviewed_by_admin_id');
            $table->unsignedBigInteger('approved_by_kasubag_id')->nullable()->after('diteruskan_ke_kasubag_date');
            $table->timestamp('approved_by_kasubag_date')->nullable()->after('approved_by_kasubag_id');
            
            // Foreign keys
            $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update peminjaman_asets table
        Schema::table('peminjaman_asets', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'direview', 'diteruskan_ke_kasubag', 'approved', 'rejected'])->change();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('diteruskan_ke_kasubag_date')->nullable()->after('reviewed_by_admin_id');
            $table->unsignedBigInteger('approved_by_kasubag_id')->nullable()->after('diteruskan_ke_kasubag_date');
            $table->timestamp('approved_by_kasubag_date')->nullable()->after('approved_by_kasubag_id');
            
            // Foreign keys
            $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_kasubag_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert request_models table
        Schema::table('request_models', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropForeign(['approved_by_kasubag_id']);
            $table->dropColumn(['reviewed_by_admin_id', 'diteruskan_ke_kasubag_date', 'approved_by_kasubag_id', 'approved_by_kasubag_date']);
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->change();
        });

        // Revert permintaan_persediaans table
        Schema::table('permintaan_persediaans', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropForeign(['approved_by_kasubag_id']);
            $table->dropColumn(['reviewed_by_admin_id', 'diteruskan_ke_kasubag_date', 'approved_by_kasubag_id', 'confirmed_by_kasubag_date']);
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->change();
        });

        // Revert peminjaman_kendaraans table
        Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropForeign(['approved_by_kasubag_id']);
            $table->dropColumn(['reviewed_by_admin_id', 'diteruskan_ke_kasubag_date', 'approved_by_kasubag_id', 'approved_by_kasubag_date']);
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->change();
        });

        // Revert peminjaman_asets table
        Schema::table('peminjaman_asets', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropForeign(['approved_by_kasubag_id']);
            $table->dropColumn(['reviewed_by_admin_id', 'diteruskan_ke_kasubag_date', 'approved_by_kasubag_id', 'approved_by_kasubag_date']);
            $table->enum('status', ['Menunggu', 'approved', 'rejected'])->change();
        });
    }
};
