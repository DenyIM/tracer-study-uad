<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada
        User::where('role', 'alumni')->delete();

        // Data alumni utama
        $user = User::create([
            'email' => '1test2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => '1Budi Santoso',
            'nim' => '123451',
            'date_of_birth' => '1999-05-10', // Opsional, bisa dihapus
            'phone' => '081298765432',
            'study_program' => 'Informatika',
            'graduation_date' => '2024-09-15',
            'npwp' => '12.345.678.9-012.345',
            'points' => 100,
        ]);

        $user = User::create([
            'email' => '2test2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => '2Budi Santoso',
            'nim' => '123452',
            'date_of_birth' => '1999-05-10', // Opsional, bisa dihapus
            'phone' => '081298765432',
            'study_program' => 'Informatika',
            'graduation_date' => '2024-09-15',
            'npwp' => '12.345.678.9-012.345',
            'points' => 100,
        ]);

        $user = User::create([
            'email' => '3test2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => '3Budi Santoso',
            'nim' => '123453',
            'date_of_birth' => '1999-05-10', // Opsional, bisa dihapus
            'phone' => '081298765432',
            'study_program' => 'Informatika',
            'graduation_date' => '2024-09-15',
            'npwp' => '12.345.678.9-012.345',
            'points' => 100,
        ]);

        $user = User::create([
            'email' => '4test2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => '4Budi Santoso',
            'nim' => '123454',
            'date_of_birth' => '1999-05-10', // Opsional, bisa dihapus
            'phone' => '081298765432',
            'study_program' => 'Informatika',
            'graduation_date' => '2024-09-15',
            'npwp' => '12.345.678.9-012.345',
            'points' => 100,
        ]);

        $user = User::create([
            'email' => '5test2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'alumni',
            'email_verified_at' => now(),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => '5Budi Santoso',
            'nim' => '123455',
            'date_of_birth' => '1999-05-10', // Opsional, bisa dihapus
            'phone' => '081298765432',
            'study_program' => 'Informatika',
            'graduation_date' => '2024-09-15',
            'npwp' => '12.345.678.9-012.345',
            'points' => 100,
        ]);

        // Data dummy tambahan
        $studyPrograms = ['Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'email' => 'alumni' . ($i + 1) . '@webmail.uad.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'alumni',
                'email_verified_at' => rand(0, 1) ? now() : null,
                'last_login_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
            ]);
            
            // Generate NIM unik: 2019 + random 5 digit
            $nim = '2019' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            
            Alumni::create([
                'user_id' => $user->id,
                'fullname' => 'Alumni ' . ($i + 1),
                'nim' => $nim,
                'date_of_birth' => now()->subYears(rand(22, 30))->format('Y-m-d'), // Opsional
                'phone' => '0812' . str_pad(rand(10000000, 99999999), 8, '0'),
                'study_program' => $studyPrograms[array_rand($studyPrograms)],
                'graduation_date' => now()->subYears(rand(1, 5))->format('Y-m-d'),
                'npwp' => rand(0, 1) ? '12.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-0' . rand(10, 99) . '.' . rand(100, 999) : null,
                'points' => rand(0, 500),
            ]);
        }
    }
}