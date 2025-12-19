<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key constraints untuk menghindari error
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ========== SEED DATA ADMIN ==========
        $this->call(AdminSeeder::class);

        // ========== SEED DATA ALUMNI (USER) ==========
        $this->call(UserSeeder::class);

        // ========== SEED DATA CATEGORIES ==========
        $this->call(CategorySeeder::class);

        // ========== SEED DATA QUESTIONNAIRES ==========
        $this->call(QuestionnaireSeeder::class);

        // ========== SEED DATA QUESTIONS ==========
        $this->call(QuestionSeeder::class);

        // ========== SEED DATA QUESTION OPTIONS (Opsional) ==========
        $this->call(QuestionOptionSeeder::class);

        // ========== SEED DATA FEATURES ==========
        // $this->call(FeatureSeeder::class);

        // ========== SEED DATA SAMPLE RESPONSES (Hanya di Local) ==========
        if (app()->environment('local')) {
            $this->call(UserResponseSeeder::class);
            $this->call(ResponseDetailSeeder::class);

            // Atau gunakan SampleResponseSeeder yang komprehensif
            // $this->call(SampleResponseSeeder::class);
        }

        // Enable foreign key constraints kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('=========================================');
        $this->command->info('ALL SEEDERS COMPLETED SUCCESSFULLY!');
        $this->command->info('=========================================');
    }
}
