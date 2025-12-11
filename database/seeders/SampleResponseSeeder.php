<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\UserResponse;
use App\Models\ResponseDetail;
use Illuminate\Database\Seeder;

class SampleResponseSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya jalankan di environment local
        if (!app()->environment('local')) {
            return;
        }

        // Ambil beberapa user
        $users = User::limit(5)->get();

        // Ambil kategori "Bekerja"
        $category = Category::where('name', 'BEKERJA DI PERUSAHAAN/INSTANSI')->first();

        if (!$category) {
            return;
        }

        // Ambil kuesioner untuk kategori ini
        $questionnaires = Questionnaire::where('category_id', $category->id)->get();

        foreach ($users as $user) {
            foreach ($questionnaires as $questionnaire) {
                // Buat response untuk setiap kuesioner
                $userResponse = UserResponse::create([
                    'user_id' => $user->id,
                    'questionnaire_id' => $questionnaire->id,
                    'category_id' => $category->id,
                    'status' => 'submitted',
                    'submitted_at' => now()->subDays(rand(1, 30)),
                    'total_questions' => $questionnaire->questions()->count(),
                    'answered_questions' => $questionnaire->questions()->count(),
                    'completion_percentage' => 100,
                ]);

                // Ambil pertanyaan untuk kuesioner ini
                $questions = $questionnaire->questions()->ordered()->get();

                foreach ($questions as $question) {
                    // Buat jawaban dummy berdasarkan tipe pertanyaan
                    $answer = $this->generateDummyAnswer($question);

                    ResponseDetail::create([
                        'user_response_id' => $userResponse->id,
                        'question_id' => $question->id,
                        'answer_text' => $answer['text'] ?? null,
                        'answer_value' => $answer['value'] ?? null,
                        'other_answer' => $answer['other'] ?? null,
                        'matrix_answers' => $answer['matrix'] ?? null,
                    ]);
                }
            }
        }
    }

    /**
     * Generate dummy answer based on question type.
     */
    private function generateDummyAnswer($question): array
    {
        $answer = [];

        switch ($question->type) {
            case 'dropdown':
            case 'radio':
                $option = $question->options()->inRandomOrder()->first();
                if ($option) {
                    if ($option->value === 999) { // "Other" option
                        $answer['value'] = null;
                        $answer['other'] = 'Sumber dana lainnya';
                    } else {
                        $answer['value'] = $option->value;
                    }
                }
                break;

            case 'checkbox':
                $selectedOptions = $question->options()->inRandomOrder()->limit(rand(1, 3))->get();
                $texts = $selectedOptions->pluck('option_text')->toArray();
                $answer['text'] = implode(', ', $texts);
                break;

            case 'text':
                $answer['text'] = 'Jawaban teks contoh';
                break;

            case 'textarea':
                $answer['text'] = 'Ini adalah jawaban panjang untuk pertanyaan esai. Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                break;

            case 'number':
                $answer['value'] = rand(3000000, 10000000);
                break;

            case 'scale':
                $answer['value'] = rand(3, 5); // Mostly positive
                break;

            case 'competency_scale':
                $competencies = ['Etika', 'Keahlian Bidang Ilmu', 'Bahasa Inggris', 'Penggunaan IT', 'Komunikasi'];
                $matrix = [];
                foreach ($competencies as $competency) {
                    $matrix[$competency] = rand(3, 5);
                }
                $answer['matrix'] = $matrix;
                break;

            case 'matrix':
                $methods = ['Perkuliahan', 'Demonstrasi', 'Partisipasi Proyek Riset', 'Magang', 'Praktikum'];
                $matrix = [];
                foreach ($methods as $method) {
                    $matrix[$method] = rand(1, 5);
                }
                $answer['matrix'] = $matrix;
                break;

            default:
                $answer['text'] = 'Jawaban default';
        }

        return $answer;
    }
}
