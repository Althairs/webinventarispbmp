<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // [
            //     'nama_lengkap' => 'Super Admin',
            //     'instansi' => 'BPMP Provinsi Gorontalo',
            //     'nip' => '1234567890',
            //     'username' => 'superadmin',
            //     'email' => 'superadmin@bpmp.go.id',
            //     'password' => Hash::make('password123'),
            //     'role' => 'super_admin',
            //     'status' => 'active',
            //     'is_suspend' => false,
            //     'role_request' => 'admin',
            //     'is_approved_by_admin' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ],
            // [
            //     'nama_lengkap' => 'Admin',
            //     'instansi' => 'BPMP Provinsi Gorontalo',
            //     'nip' => '0987654321',
            //     'username' => 'admin',
            //     'email' => 'admin@bpmp.go.id',
            //     'password' => Hash::make('password123'),
            //     'role' => 'admin',
            //     'status' => 'active',
            //     'is_suspend' => false,
            //     'role_request' => 'admin',
            //     'is_approved_by_admin' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ],
            // [
            //     'nama_lengkap' => 'Lintang Cahyani',
            //     'instansi' => 'BPMP Provinsi Gorontalo',
            //     'nip' => '1122334455',
            //     'username' => 'lintang',
            //     'email' => 'lintangcahyani@bpmp.go.id',
            //     'password' => Hash::make('password123'),
            //     'role' => 'user',
            //     'status' => 'active',
            //     'is_suspend' => false,
            //     'role_request' => 'user',
            //     'is_approved_by_admin' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ],
            // [
            //     'nama_lengkap' => 'Dr. Hj. Fatimah S.Pd, M.Pd',
            //     'instansi' => 'BPMP Provinsi Gorontalo',
            //     'nip' => '19680315 198803 2 001',
            //     'username' => 'kasubag1',
            //     'email' => 'kasubag@bpmp.go.id',
            //     'password' => Hash::make('kasubag123'),
            //     'role' => 'kasubag',
            //     'status' => 'active',
            //     'is_suspend' => false,
            //     'role_request' => 'admin',
            //     'is_approved_by_admin' => true,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now(),
            // ],
            [
                'nama_lengkap' => 'Admin',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '09876543251',
                'username' => 'BPMP',
                'email' => 'bpmp@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'kepala_bpmp',
                'status' => 'active',
                'is_suspend' => false,
                'role_request' => 'admin',
                'is_approved_by_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_lengkap' => 'Admin',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '0983654321',
                'username' => 'admin',
                'email' => 'tamu@bpmp.go.id',
                'password' => Hash::make('password123'),
                'role' => 'tamu',
                'status' => 'active',
                'is_suspend' => false,
                'role_request' => 'user',
                'is_approved_by_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
