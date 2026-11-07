<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Semua role yang valid untuk kolom users.role dan users.role_request.
     *
     * @var list<string>
     */
    private array $roles = [
        'super_admin',
        'admin',
        'user',
        'kasubag',
        'kepala_bpmp',
        'tamu',
    ];

    /**
     * Pastikan role 'kasubag' dan 'kepala_bpmp' selalu ada di enum users.
     *
     * Migration ini diperlukan karena migration lama
     * (2025_11_11_000001_update_users_add_nomor_urut_and_tamu) pernah
     * menimpa enum role sehingga 'kasubag' dan 'kepala_bpmp' hilang.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        // Hindari error saat mengubah kolom menjadi NOT NULL.
        DB::table('users')->whereNull('role')->update(['role' => 'user']);
        DB::table('users')->whereNull('role_request')->update(['role_request' => 'user']);

        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $allowed = "'" . implode("','", $this->roles) . "'";

            DB::statement("ALTER TABLE users MODIFY role ENUM($allowed) NOT NULL DEFAULT 'user'");
            DB::statement("ALTER TABLE users MODIFY role_request ENUM($allowed) NOT NULL DEFAULT 'user'");

            return;
        }

        // Fallback untuk SQLite/PostgreSQL yang tidak memakai sintaks ENUM MySQL.
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', $this->roles)->default('user')->change();
            $table->enum('role_request', $this->roles)->default('user')->change();
        });
    }

    /**
     * Tidak mengembalikan enum ke kondisi lama agar role yang sudah dipakai
     * (kasubag/kepala_bpmp) tidak hilang saat rollback.
     */
    public function down(): void
    {
        //
    }
};
