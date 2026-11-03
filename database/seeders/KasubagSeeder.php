<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KasubagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama_lengkap' => 'Dr. Hj. Fatimah S.Pd, M.Pd',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '19680315 198803 2 001',
                'username' => 'kasubag1',
                'email' => 'kasubag@bpmp.go.id',
                'password' => Hash::make('kasubag123'),
                'role' => 'kasubag',
                'status' => 'active',
                'is_suspend' => false,
                'is_approved_by_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_lengkap' => 'Bambang Sutrisno, S.Sos, M.Pd',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '19651203 198903 1 001',
                'username' => 'kasubag2',
                'email' => 'kasubag.program@bpmp.go.id',
                'password' => Hash::make('kasubag123'),
                'role' => 'kasubag',
                'status' => 'active',
                'is_suspend' => false,
                'is_approved_by_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_lengkap' => 'Dra. Sri Wahyuni, M.A.P',
                'instansi' => 'BPMP Provinsi Gorontalo',
                'nip' => '19640720 199203 2 001',
                'username' => 'kasubag3',
                'email' => 'kasubag.umum@bpmp.go.id',
                'password' => Hash::make('kasubag123'),
                'role' => 'kasubag',
                'status' => 'active',
                'is_suspend' => false,
                'is_approved_by_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
