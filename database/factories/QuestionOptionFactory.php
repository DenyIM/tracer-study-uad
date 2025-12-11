<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = Question::factory()->create();

        // Generate options based on question type
        $optionData = $this->generateOptionData($question);

        return [
            'question_id' => $question->id,
            'option_text' => $optionData['text'],
            'value' => $optionData['value'],
            'label' => $optionData['label'],
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Generate option data based on question type.
     */
    protected function generateOptionData(Question $question): array
    {
        $data = [
            'text' => $this->faker->sentence(),
            'value' => null,
            'label' => null,
        ];

        switch ($question->type) {
            case 'dropdown':
            case 'radio':
            case 'checkbox':
                // For choice questions, assign sequential values
                $data['value'] = $this->faker->numberBetween(1, 10);
                break;

            case 'scale':
            case 'competency_scale':
                // For scale questions, assign value 1-5 with labels
                $value = $this->faker->numberBetween(1, 5);
                $labels = [
                    1 => 'Sangat Rendah',
                    2 => 'Rendah',
                    3 => 'Cukup',
                    4 => 'Tinggi',
                    5 => 'Sangat Tinggi',
                ];
                $data['value'] = $value;
                $data['label'] = $labels[$value] ?? null;
                $data['text'] = $labels[$value] ?? "Skala {$value}";
                break;

            case 'matrix':
                // For matrix questions, option_text is the row item
                $items = ['Etika', 'Keahlian', 'Bahasa Inggris', 'IT', 'Komunikasi', 'Teamwork', 'Pengembangan Diri'];
                $data['text'] = $this->faker->randomElement($items);
                break;
        }

        // 10% chance for "Other" option
        if ($question->has_other_option && $this->faker->boolean(10)) {
            $data['text'] = 'Lainnya, sebutkan!';
            $data['value'] = 999; // Special value for "Other"
        }

        return $data;
    }

    /**
     * Indicate a specific question.
     */
    public function forQuestion(Question $question): static
    {
        return $this->state(fn(array $attributes) => [
            'question_id' => $question->id,
        ]);
    }

    /**
     * Indicate a specific order.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'order' => $order,
        ]);
    }

    /**
     * Indicate a specific option text.
     */
    public function withText(string $text): static
    {
        return $this->state(fn(array $attributes) => [
            'option_text' => $text,
        ]);
    }

    /**
     * Indicate a specific value.
     */
    public function withValue(int $value): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => $value,
        ]);
    }

    /**
     * Indicate a specific label.
     */
    public function withLabel(string $label): static
    {
        return $this->state(fn(array $attributes) => [
            'label' => $label,
        ]);
    }

    /**
     * Indicate a scale option (1-5).
     */
    public function scaleOption(int $value = null): static
    {
        $value = $value ?? $this->faker->numberBetween(1, 5);

        $labels = [
            1 => 'Sangat Rendah',
            2 => 'Rendah',
            3 => 'Cukup',
            4 => 'Tinggi',
            5 => 'Sangat Tinggi',
        ];

        return $this->state(fn(array $attributes) => [
            'option_text' => $labels[$value] ?? "Skala {$value}",
            'value' => $value,
            'label' => $labels[$value] ?? null,
        ]);
    }

    /**
     * Indicate a routing option for F8 question.
     */
    public function routingOption(): static
    {
        $options = [
            'Bekerja (full time/part time) di perusahaan/instansi',
            'Wiraswasta/Pemilik Usaha',
            'Melanjutkan Pendidikan',
            'Tidak Kerja, tetapi sedang mencari kerja',
            'Belum memungkinkan bekerja / Tidak mencari kerja',
        ];

        return $this->state(fn(array $attributes) => [
            'option_text' => $this->faker->randomElement($options),
            'value' => $this->faker->numberBetween(1, 5),
            'label' => null,
        ]);
    }

    /**
     * Indicate a funding source option for F12 question.
     */
    public function fundingOption(): static
    {
        $options = [
            'Biaya Sendiri/Keluarga',
            'Beasiswa ADIK',
            'Beasiswa BIDIKMISI',
            'Beasiswa PPA',
            'Beasiswa AFIRMASI',
            'Beasiswa Perusahaan/Swasta',
        ];

        return $this->state(fn(array $attributes) => [
            'option_text' => $this->faker->randomElement($options),
            'value' => $this->faker->numberBetween(1, 6),
            'label' => null,
        ]);
    }

    /**
     * Indicate "Other" option.
     */
    public function otherOption(): static
    {
        return $this->state(fn(array $attributes) => [
            'option_text' => 'Lainnya, sebutkan!',
            'value' => 999,
            'label' => 'Other',
        ]);
    }

    /**
     * Indicate a time duration option for F502 question.
     */
    public function durationOption(): static
    {
        $options = [
            'Belum mendapat pekerjaan',
            '0-<3 bulan',
            '3-<6 bulan',
            '6-<9 bulan',
            '9-<12 bulan',
            '>12 bulan',
        ];

        $option = $this->faker->randomElement($options);
        $value = array_search($option, $options) + 1;

        return $this->state(fn(array $attributes) => [
            'option_text' => $option,
            'value' => $value,
            'label' => null,
        ]);
    }

    /**
     * Indicate a learning method option for F21-F27 question.
     */
    public function learningMethodOption(): static
    {
        $methods = [
            'Perkuliahan',
            'Demonstrasi',
            'Partisipasi Proyek Riset',
            'Magang',
            'Praktikum',
            'Kerja Lapangan',
            'Diskusi',
        ];

        return $this->state(fn(array $attributes) => [
            'option_text' => $this->faker->randomElement($methods),
            'value' => null,
            'label' => null,
        ]);
    }

    /**
     * Indicate a competency item option.
     */
    public function competencyItemOption(): static
    {
        $competencies = [
            'Etika',
            'Keahlian Bidang Ilmu',
            'Bahasa Inggris',
            'Penggunaan IT',
            'Komunikasi',
            'Kerja Sama Tim',
            'Pengembangan Diri',
        ];

        return $this->state(fn(array $attributes) => [
            'option_text' => $this->faker->randomElement($competencies),
            'value' => null,
            'label' => null,
        ]);
    }
}
