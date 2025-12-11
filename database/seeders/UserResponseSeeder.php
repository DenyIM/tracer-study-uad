<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\UserResponse;
use Illuminate\Database\Seeder;

class UserResponseSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya jalankan di environment local
        if (!app()->environment('local')) {
            return;
        }

        // Kosongkan tabel terlebih dahulu
        UserResponse::truncate();

        // Ambil semua user
        $users = User::all();

        // Ambil semua kategori
        $categories = Category::all();

        // Status kemungkinan dengan distribusi
        $statusOptions = [
            'draft' => 20,    // 20% draft
            'submitted' => 60, // 60% submitted
            'approved' => 20,  // 20% approved
        ];

        foreach ($users as $user) {
            foreach ($categories as $category) {
                // Tentukan apakah user akan mengisi kuesioner untuk kategori ini
                if ($this->faker()->boolean(70)) { // 70% chance user mengisi kategori ini
                    // Ambil kuesioner untuk kategori ini
                    $questionnaires = Questionnaire::where('category_id', $category->id)
                        ->active()
                        ->ordered()
                        ->get();

                    foreach ($questionnaires as $questionnaire) {
                        // Tentukan status response
                        $status = $this->getRandomStatus($statusOptions);

                        // Hitung total pertanyaan
                        $totalQuestions = $questionnaire->questions()->count();

                        // Tentukan berapa banyak pertanyaan yang dijawab
                        if ($status === 'draft') {
                            $answeredQuestions = rand(0, $totalQuestions);
                        } else {
                            $answeredQuestions = $totalQuestions; // Submitted/approved harus lengkap
                        }

                        // Hitung persentase
                        $completionPercentage = $totalQuestions > 0
                            ? round(($answeredQuestions / $totalQuestions) * 100, 2)
                            : 0;

                        // Tentukan tanggal submit
                        $submittedAt = null;
                        if (in_array($status, ['submitted', 'approved'])) {
                            $submittedAt = now()->subDays(rand(1, 90));
                        }

                        // Buat response
                        $userResponse = UserResponse::create([
                            'user_id' => $user->id,
                            'questionnaire_id' => $questionnaire->id,
                            'category_id' => $category->id,
                            'status' => $status,
                            'submitted_at' => $submittedAt,
                            'total_questions' => $totalQuestions,
                            'answered_questions' => $answeredQuestions,
                            'completion_percentage' => $completionPercentage,
                            'created_at' => $submittedAt ?: now()->subDays(rand(1, 30)),
                            'updated_at' => $submittedAt ?: now()->subDays(rand(0, 29)),
                        ]);

                        // Jika ada pertanyaan yang dijawab, buat juga ResponseDetail
                        if ($answeredQuestions > 0) {
                            // Seed ResponseDetail melalui seeder terpisah
                            // atau buat langsung di sini
                        }
                    }
                }
            }
        }

        $this->command->info('UserResponse seeded successfully!');
        $this->command->info('Total responses created: ' . UserResponse::count());
    }

    /**
     * Get random status based on distribution.
     */
    private function getRandomStatus(array $statusOptions): string
    {
        $total = array_sum($statusOptions);
        $random = rand(1, $total);

        $cumulative = 0;
        foreach ($statusOptions as $status => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $status;
            }
        }

        return 'draft'; // fallback
    }

    /**
     * Get Faker instance.
     */
    private function faker()
    {
        return \Faker\Factory::create();
    }
}
