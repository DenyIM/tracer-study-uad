<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya jalankan jika tabel question_options masih kosong
        if (QuestionOption::count() > 0) {
            $this->command->info('QuestionOption table is not empty. Skipping seeder.');
            return;
        }

        $this->command->info('Seeding QuestionOption...');

        // Ambil semua pertanyaan yang belum memiliki opsi
        $questions = Question::whereDoesntHave('options')->get();

        $totalOptions = 0;

        foreach ($questions as $question) {
            $options = $this->generateOptionsForQuestion($question);

            foreach ($options as $optionData) {
                QuestionOption::create(array_merge($optionData, [
                    'question_id' => $question->id,
                ]));

                $totalOptions++;
            }
        }

        $this->command->info('QuestionOption seeded successfully!');
        $this->command->info('Total options created: ' . $totalOptions);
    }

    /**
     * Generate options based on question type and code.
     */
    private function generateOptionsForQuestion(Question $question): array
    {
        $options = [];

        switch ($question->code) {
            case 'F8': // Status saat ini
                $options = [
                    ['option_text' => 'Bekerja (full time/part time) di perusahaan/instansi', 'value' => 1, 'order' => 1],
                    ['option_text' => 'Wiraswasta/Pemilik Usaha', 'value' => 2, 'order' => 2],
                    ['option_text' => 'Melanjutkan Pendidikan', 'value' => 3, 'order' => 3],
                    ['option_text' => 'Tidak Kerja, tetapi sedang mencari kerja', 'value' => 4, 'order' => 4],
                    ['option_text' => 'Belum memungkinkan bekerja / Tidak mencari kerja', 'value' => 5, 'order' => 5],
                ];
                break;

            case 'F502': // Lama dapat pekerjaan
                $options = [
                    ['option_text' => 'Belum mendapat pekerjaan', 'value' => 1, 'order' => 1],
                    ['option_text' => '0-<3 bulan', 'value' => 2, 'order' => 2],
                    ['option_text' => '3-<6 bulan', 'value' => 3, 'order' => 3],
                    ['option_text' => '6-<9 bulan', 'value' => 4, 'order' => 4],
                    ['option_text' => '9-<12 bulan', 'value' => 5, 'order' => 5],
                    ['option_text' => '>12 bulan', 'value' => 6, 'order' => 6],
                ];
                break;

            case 'F12': // Sumber dana kuliah
                $options = [
                    ['option_text' => 'Biaya Sendiri/Keluarga', 'value' => 1, 'order' => 1],
                    ['option_text' => 'Beasiswa ADIK', 'value' => 2, 'order' => 2],
                    ['option_text' => 'Beasiswa BIDIKMISI', 'value' => 3, 'order' => 3],
                    ['option_text' => 'Beasiswa PPA', 'value' => 4, 'order' => 4],
                    ['option_text' => 'Beasiswa AFIRMASI', 'value' => 5, 'order' => 5],
                    ['option_text' => 'Beasiswa Perusahaan/Swasta', 'value' => 6, 'order' => 6],
                    ['option_text' => 'Lainnya, sebutkan!', 'value' => 999, 'order' => 7],
                ];
                break;

            case 'F30': // Relevansi pekerjaan
            case null: // Untuk pertanyaan skala umum
                if ($question->type === 'scale') {
                    $scaleLabels = $this->getScaleLabels($question);
                    foreach ($scaleLabels as $value => $label) {
                        $options[] = [
                            'option_text' => $label,
                            'value' => $value,
                            'label' => $label,
                            'order' => $value,
                        ];
                    }
                }
                break;

            default:
                // Generate default options berdasarkan tipe
                $options = $this->generateDefaultOptions($question);
        }

        return $options;
    }

    /**
     * Get scale labels based on question.
     */
    private function getScaleLabels(Question $question): array
    {
        // Cek dari deskripsi atau konteks pertanyaan
        if (str_contains(strtolower($question->question_text), 'relevan')) {
            return [
                1 => 'Tidak Relevan',
                2 => 'Kurang Relevan',
                3 => 'Cukup Relevan',
                4 => 'Relevan',
                5 => 'Sangat Relevan',
            ];
        }

        if (str_contains(strtolower($question->question_text), 'puas')) {
            return [
                1 => 'Sangat Tidak Puas',
                2 => 'Tidak Puas',
                3 => 'Cukup Puas',
                4 => 'Puas',
                5 => 'Sangat Puas',
            ];
        }

        if (str_contains(strtolower($question->question_text), 'besar')) {
            return [
                1 => 'Sangat Kecil',
                2 => 'Kecil',
                3 => 'Cukup Besar',
                4 => 'Besar',
                5 => 'Sangat Besar',
            ];
        }

        // Default scale
        return [
            1 => 'Sangat Rendah',
            2 => 'Rendah',
            3 => 'Cukup',
            4 => 'Tinggi',
            5 => 'Sangat Tinggi',
        ];
    }

    /**
     * Generate default options based on question type.
     */
    private function generateDefaultOptions(Question $question): array
    {
        $faker = \Faker\Factory::create();
        $options = [];

        switch ($question->type) {
            case 'dropdown':
            case 'radio':
                $count = rand(3, 6);
                for ($i = 1; $i <= $count; $i++) {
                    $options[] = [
                        'option_text' => $faker->sentence(),
                        'value' => $i,
                        'order' => $i,
                    ];
                }

                // Tambahkan opsi "Lainnya" jika diperlukan
                if ($question->has_other_option) {
                    $options[] = [
                        'option_text' => 'Lainnya, sebutkan!',
                        'value' => 999,
                        'order' => $count + 1,
                    ];
                }
                break;

            case 'checkbox':
                $count = rand(3, 8);
                for ($i = 1; $i <= $count; $i++) {
                    $options[] = [
                        'option_text' => $faker->words(3, true),
                        'value' => $i,
                        'order' => $i,
                    ];
                }

                if ($question->has_other_option) {
                    $options[] = [
                        'option_text' => 'Lainnya',
                        'value' => 999,
                        'order' => $count + 1,
                    ];
                }
                break;

            case 'scale':
                $labels = $this->getScaleLabels($question);
                foreach ($labels as $value => $label) {
                    $options[] = [
                        'option_text' => $label,
                        'value' => $value,
                        'label' => $label,
                        'order' => $value,
                    ];
                }
                break;
        }

        return $options;
    }
}
