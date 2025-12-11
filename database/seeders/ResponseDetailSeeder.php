<?php

namespace Database\Seeders;

use App\Models\UserResponse;
use App\Models\ResponseDetail;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class ResponseDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya jalankan di environment local
        if (!app()->environment('local')) {
            return;
        }

        // Kosongkan tabel terlebih dahulu
        ResponseDetail::truncate();

        // Ambil semua user response yang memiliki answered_questions > 0
        $userResponses = UserResponse::where('answered_questions', '>', 0)->get();

        $totalDetails = 0;

        foreach ($userResponses as $userResponse) {
            // Ambil pertanyaan untuk kuesioner ini
            $questions = Question::where('questionnaire_id', $userResponse->questionnaire_id)
                ->ordered()
                ->get();

            // Tentukan berapa banyak pertanyaan yang akan dijawab (sesuai answered_questions)
            $questionsToAnswer = $questions->take($userResponse->answered_questions);

            foreach ($questionsToAnswer as $question) {
                // Generate jawaban berdasarkan tipe pertanyaan
                $answerData = $this->generateAnswerForQuestion($question);

                // Buat response detail
                ResponseDetail::create([
                    'user_response_id' => $userResponse->id,
                    'question_id' => $question->id,
                    'answer_text' => $answerData['text'] ?? null,
                    'answer_value' => $answerData['value'] ?? null,
                    'other_answer' => $answerData['other'] ?? null,
                    'matrix_answers' => $answerData['matrix'] ?? null,
                    'created_at' => $userResponse->created_at,
                    'updated_at' => $userResponse->updated_at,
                ]);

                $totalDetails++;
            }
        }

        $this->command->info('ResponseDetail seeded successfully!');
        $this->command->info('Total response details created: ' . $totalDetails);
    }

    /**
     * Generate answer based on question type.
     */
    private function generateAnswerForQuestion(Question $question): array
    {
        $faker = \Faker\Factory::create();
        $answer = [];

        switch ($question->type) {
            case 'dropdown':
            case 'radio':
                if ($question->options()->exists()) {
                    $options = $question->options()->ordered()->get();
                    $option = $options->random();

                    if ($option->value === 999) { // "Other" option
                        $answer['other'] = $faker->sentence();
                    } else {
                        $answer['value'] = $option->value;
                    }
                } else {
                    $answer['value'] = $faker->numberBetween(1, 5);
                }
                break;

            case 'checkbox':
                if ($question->options()->exists()) {
                    $options = $question->options()->ordered()->get();
                    $selectedCount = rand(1, min(3, $options->count()));
                    $selectedOptions = $options->random($selectedCount);

                    $texts = [];
                    $hasOther = false;
                    $otherText = '';

                    foreach ($selectedOptions as $option) {
                        if ($option->value === 999) {
                            $hasOther = true;
                            $otherText = $faker->sentence();
                        } else {
                            $texts[] = $option->option_text;
                        }
                    }

                    if (!empty($texts)) {
                        $answer['text'] = implode(', ', $texts);
                    }

                    if ($hasOther) {
                        $answer['other'] = $otherText;
                    }
                } else {
                    $answer['text'] = $faker->words(3, true);
                }
                break;

            case 'text':
                $answer['text'] = $faker->sentence();
                break;

            case 'textarea':
                $answer['text'] = $faker->paragraph();
                break;

            case 'number':
                // Untuk gaji (F31)
                if ($question->code === 'F31') {
                    $answer['value'] = $faker->numberBetween(3000000, 15000000);
                } else {
                    $answer['value'] = $faker->numberBetween(1, 100);
                }
                break;

            case 'date':
                $answer['text'] = $faker->date();
                break;

            case 'scale':
                $answer['value'] = $faker->numberBetween(1, 5);
                break;

            case 'competency_scale':
                // Untuk F17.A - Kompetensi
                $competencies = [
                    'Etika',
                    'Keahlian Bidang Ilmu',
                    'Bahasa Inggris',
                    'Penggunaan IT',
                    'Komunikasi',
                    'Kerja Sama Tim',
                    'Pengembangan Diri',
                ];

                $matrix = [];
                foreach ($competencies as $competency) {
                    $matrix[$competency] = $faker->numberBetween(3, 5); // Mostly good ratings
                }
                $answer['matrix'] = $matrix;
                break;

            case 'matrix':
                // Untuk F21-F27 - Metode Pembelajaran
                $methods = [
                    'Perkuliahan',
                    'Demonstrasi',
                    'Partisipasi Proyek Riset',
                    'Magang',
                    'Praktikum',
                    'Kerja Lapangan',
                    'Diskusi',
                ];

                $matrix = [];
                foreach ($methods as $method) {
                    $matrix[$method] = $faker->numberBetween(3, 5);
                }
                $answer['matrix'] = $matrix;
                break;

            default:
                $answer['text'] = $faker->sentence();
        }

        // Handle specific questions
        switch ($question->code) {
            case 'F8': // Status saat ini
                $options = ['Bekerja', 'Wiraswasta', 'Pendidikan', 'Pencari Kerja', 'Tidak Bekerja'];
                $answer['value'] = $faker->numberBetween(1, 5);
                break;

            case 'F502': // Lama dapat pekerjaan
                $answer['value'] = $faker->numberBetween(1, 6);
                break;

            case 'F12': // Sumber dana
                if ($faker->boolean(20)) { // 20% pilih "Lainnya"
                    $answer['value'] = null;
                    $answer['other'] = 'Beasiswa dari perusahaan orang tua';
                } else {
                    $answer['value'] = $faker->numberBetween(1, 6);
                }
                break;
        }

        return $answer;
    }
}
