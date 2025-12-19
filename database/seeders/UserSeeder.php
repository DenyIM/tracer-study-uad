<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Alumni;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        User::truncate();

        $user = User::create([
            'email' => 'alumni@tracer.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => 'Budi Santoso',
            'nim' => '20190001',
            'date_of_birth' => '1999-05-10',
            'phone' => '081298765432',
            'study_program' => 'Teknik Informatika',
            'graduation_date' => '2023-09-15',
            'npwp' => '12.345.678.9-012.345',
        ]);

        // Data dummy alumni hanya di local
        if (app()->environment('local')) {
            User::factory(17)->create(); // Total 20 user (3 di atas + 17 factory)
        }
    }
}
