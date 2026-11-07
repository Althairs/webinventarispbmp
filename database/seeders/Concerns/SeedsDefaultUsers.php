<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

trait SeedsDefaultUsers
{
    /**
     * Tambahkan user hanya jika belum ada.
     *
     * Pengecekan dilakukan terhadap semua kolom unique (username, email, nip)
     * agar seeder tidak pernah gagal karena duplicate entry sekaligus tidak
     * menimpa akun yang sudah ada (misalnya akun admin yang sudah dipakai).
     */
    protected function seedUserIfMissing(array $data): void
    {
        $exists = DB::table('users')
            ->where('username', $data['username'])
            ->orWhere('email', $data['email'])
            ->orWhere('nip', $data['nip'])
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('users')->insert($data);
    }
}
