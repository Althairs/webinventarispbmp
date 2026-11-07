<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsDefaultUsers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    use SeedsDefaultUsers;

    /**
     * Run the database seeds.
     *
     * Seeder ini idempotent dan tidak menimpa akun yang sudah ada.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            [
                'nama_lengkap' => 'Super Admin',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '09876543251',
                'username' => 'superadmin',
                'email' => 'super_admin@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'status' => 'active',
                'is_suspend' => false,
                // 'role_request' => 'kepala_bpmp',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_lengkap' => 'Admin',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '09876543251',
                'username' => 'admin',
                'email' => 'admin@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'is_suspend' => false,
                'role_request' => 'admin',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_lengkap' => 'Lintang cahyani Putri',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '09876543251',
                'username' => 'lintang',
                'email' => 'lintang@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'is_suspend' => false,
                'role_request' => 'user',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_lengkap' => 'Tamu',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '0983654321',
                'username' => 'tamu',
                'email' => 'tamu@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'tamu',
                'status' => 'active',
                'is_suspend' => false,
                'role_request' => 'tamu',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($users as $user) {
            $this->seedUserIfMissing($user);
        }
    }
}
