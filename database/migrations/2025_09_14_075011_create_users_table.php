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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Kolom yang telah disesuaikan untuk kebutuhan sistem
            $table->string('nama_lengkap');
            $table->string('instansi');
            $table->string('nip')->unique();
            $table->string('username')->unique();

            // Kolom standar tetap dipertahankan
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'admin', 'user', 'kasubag'])->default('user');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_suspend')->default(false);

            // Kolom baru untuk permintaan role
            $table->enum('role_request', ['admin', 'user'])->default('user'); // TAMBAHKAN BARIS INI

            // Kolom baru untuk persetujuan admin
            $table->boolean('is_approved_by_admin')->default(false);

            $table->string('signature_image_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
