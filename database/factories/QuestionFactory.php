<?php

namespace Database\Factories;

use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['dropdown', 'radio', 'checkbox', 'text', 'textarea', 'number', 'date', 'scale', 'competency_scale', 'matrix'];
        $type = $this->faker->randomElement($types);

        $questionCodes = ['F8', 'F502', 'F12', 'F17.A', 'F21-F27', 'F30', 'F31', 'F40'];
        $code = $this->faker->randomElement($questionCodes);

        $questionTexts = [
            'Jelaskan status Anda saat ini?',
            'Berapa lama Anda mendapat pekerjaan sebagai karyawan/wirausaha pertama kali?',
            'Sebutkan sumber dana utama pembiayaan kuliah S1 Anda?',
            'Pada saat lulus, pada tingkat mana Anda menguasai kompetensi berikut?',
            'Menurut Anda, seberapa besar penekanan metode pembelajaran berikut di prodi Anda?',
            'Seberapa relevan pekerjaan Anda saat ini dengan bidang studi Anda?',
            'Berapa gaji/penghasilan pertama Anda setelah lulus?',
            'Apa saran Anda untuk pengembangan program studi?',
        ];

        $descriptions = [
            'Pertanyaan Pemisah Rute - Pilih satu opsi yang sesuai',
            'Pilih satu opsi yang sesuai',
            '',
            '1 = Sangat Rendah, 5 = Sangat Tinggi',
            '',
            'Pilih skala 1-5',
            'Dalam Rupiah per bulan',
            'Tuliskan saran Anda untuk pengembangan',
        ];

        $index = $this->faker->numberBetween(0, 7);

        return [
            'questionnaire_id' => Questionnaire::factory(),
            'code' => $code,
            'question_text' => $questionTexts[$index],
            'description' => $descriptions[$index],
            'type' => $type,
            'order' => $this->faker->numberBetween(1, 20),
            'is_required' => $this->faker->boolean(80), // 80% chance required
            'has_other_option' => $type === 'dropdown' ? $this->faker->boolean(30) : false,
            'validation_rules' => $type === 'number' ? ['min:0', 'max:1000000000'] : null,
        ];
    }

    /**
     * Indicate that the question is required.
     */
    public function required(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Indicate that the question is optional.
     */
    public function optional(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => false,
        ]);
    }

    /**
     * Indicate a specific question type.
     */
    public function withType(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate a dropdown question.
     */
    public function dropdown(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'dropdown',
            'has_other_option' => $this->faker->boolean(30),
        ]);
    }

    /**
     * Indicate a radio question.
     */
    public function radio(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'radio',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a checkbox question.
     */
    public function checkbox(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'checkbox',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a text question.
     */
    public function text(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'text',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a textarea question.
     */
    public function textarea(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'textarea',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a number question.
     */
    public function number(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'number',
            'has_other_option' => false,
            'validation_rules' => ['numeric', 'min:0'],
        ]);
    }

    /**
     * Indicate a scale question.
     */
    public function scale(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'scale',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a competency scale question.
     */
    public function competencyScale(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'competency_scale',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a matrix question.
     */
    public function matrix(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'matrix',
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate a specific questionnaire.
     */
    public function forQuestionnaire(Questionnaire $questionnaire): static
    {
        return $this->state(fn(array $attributes) => [
            'questionnaire_id' => $questionnaire->id,
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
     * Indicate a specific question code.
     */
    public function withCode(string $code): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => $code,
        ]);
    }

    /**
     * Indicate F8 question (routing question).
     */
    public function f8(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'F8',
            'question_text' => 'Jelaskan status Anda saat ini?',
            'description' => 'Pertanyaan Pemisah Rute - Pilih satu opsi yang sesuai',
            'type' => 'dropdown',
            'is_required' => true,
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate F502 question.
     */
    public function f502(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'F502',
            'question_text' => 'Berapa lama Anda mendapat pekerjaan sebagai karyawan/wirausaha pertama kali?',
            'description' => 'Pilih satu opsi yang sesuai',
            'type' => 'radio',
            'is_required' => true,
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate F12 question.
     */
    public function f12(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'F12',
            'question_text' => 'Sebutkan sumber dana utama pembiayaan kuliah S1 Anda?',
            'description' => '',
            'type' => 'dropdown',
            'is_required' => true,
            'has_other_option' => true,
        ]);
    }

    /**
     * Indicate F17.A question (competency scale).
     */
    public function f17a(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'F17.A',
            'question_text' => 'Pada saat lulus, pada tingkat mana Anda menguasai kompetensi berikut?',
            'description' => '1 = Sangat Rendah, 5 = Sangat Tinggi',
            'type' => 'competency_scale',
            'is_required' => true,
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate F21-F27 question (learning methods).
     */
    public function f21f27(): static
    {
        return $this->state(fn(array $attributes) => [
            'code' => 'F21-F27',
            'question_text' => 'Menurut Anda, seberapa besar penekanan metode pembelajaran berikut di prodi Anda?',
            'description' => '',
            'type' => 'matrix',
            'is_required' => true,
            'has_other_option' => false,
        ]);
    }

    /**
     * Indicate that the question has other option.
     */
    public function withOtherOption(): static
    {
        return $this->state(fn(array $attributes) => [
            'has_other_option' => true,
        ]);
    }

    /**
     * Indicate specific question text.
     */
    public function withText(string $text): static
    {
        return $this->state(fn(array $attributes) => [
            'question_text' => $text,
        ]);
    }

    /**
     * Indicate specific description.
     */
    public function withDescription(string $description): static
    {
        return $this->state(fn(array $attributes) => [
            'description' => $description,
        ]);
    }
}
