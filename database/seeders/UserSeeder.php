<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        User::truncate();

        // Data alumni contoh utama
        User::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'nim' => 'UAD2021001',
            'program_studi' => 'Teknik Informatika',
            'tanggal_lulus' => '2023-07-20',
            'npwp' => '12.345.678.9-012.345',
            'no_hp' => '6281122334455',
            'status' => 'active',
            'password' => Hash::make('Password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'nama_lengkap' => 'Siti Aminah',
            'email' => 'siti.aminah@example.com',
            'nim' => 'UAD2021002',
            'program_studi' => 'Manajemen',
            'tanggal_lulus' => '2022-06-30',
            'npwp' => null,
            'no_hp' => '628987654321',
            'status' => 'active',
            'password' => Hash::make('Password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'nama_lengkap' => 'Deny Iqbal',
            'email' => 'deny.iqbal@example.com',
            'nim' => 'UAD2018001',
            'program_studi' => 'Teknik Informatika',
            'tanggal_lulus' => '2022-08-15',
            'npwp' => '12.345.678.9-123.456',
            'no_hp' => '628999888777',
            'status' => 'active',
            'password' => Hash::make('Password123'),
            'email_verified_at' => now(),
        ]);

        // Data dummy alumni hanya di local
        if (app()->environment('local')) {
            User::factory(17)->create(); // Total 20 user (3 di atas + 17 factory)
        }
    }
}
