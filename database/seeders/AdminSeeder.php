<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'email' => 'admin2100018138@webmail.uad.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Admin::create([
            'user_id' => $user->id,
            'fullname' => 'Administrator Tracer Study',
            'phone' => '081234567890',
            'job_title' => 'System Administrator',
        ]);
    }
}
