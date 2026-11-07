<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsDefaultUsers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KasubagSeeder extends Seeder
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

        $kasubags = [
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
                'role_request' => 'kasubag',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
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
                'role_request' => 'kasubag',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
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
                'role_request' => 'kasubag',
                'is_approved_by_admin' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($kasubags as $kasubag) {
            $this->seedUserIfMissing($kasubag);
        }
    }
}
