<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        Admin::truncate();

        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@tracerstudy.uad.ac.id',
            'username' => 'superadmin',
            'phone' => '6281122334455',
            'role' => 'super_admin',
            'password' => Hash::make('SuperAdmin123!'),
        ]);

        Admin::create([
            'name' => 'Admin Utama',
            'email' => 'admin@tracerstudy.uad.ac.id',
            'username' => 'admin',
            'phone' => '6282233445566',
            'role' => 'admin',
            'password' => Hash::make('Admin123!'),
        ]);

        Admin::create([
            'name' => 'Operator Data',
            'email' => 'operator@tracerstudy.uad.ac.id',
            'username' => 'operator',
            'phone' => '6283344556677',
            'role' => 'operator',
            'password' => Hash::make('Operator123!'),
        ]);

        // Tambahkan dummy admin hanya di local
        if (app()->environment('local')) {
            \App\Models\Admin::factory(3)->create();
        }
    }
}
