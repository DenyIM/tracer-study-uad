<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionnaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questionnaireNames = [
            'Kuesioner 1 (Umum)',
            'Kuesioner 2',
            'Kuesioner 3',
            'Kuesioner 4',
        ];

        $questionnaireTitles = [
            'Data Diri & Pendidikan',
            'Pengalaman Kerja & Karir',
            'Keterampilan & Kompetensi',
            'Kepuasan & Saran',
        ];

        $descriptions = [
            'Kuesioner tentang informasi pribadi dan riwayat pendidikan di UAD.',
            'Kuesioner tentang pengalaman kerja setelah lulus dari UAD.',
            'Kuesioner tentang keterampilan yang diperoleh selama kuliah dan pengembangannya.',
            'Kuesioner tentang kepuasan terhadap pendidikan di UAD dan saran pengembangan.',
        ];

        $index = $this->faker->numberBetween(0, 3);

        return [
            'category_id' => Category::factory(),
            'name' => $questionnaireNames[$index],
            'title' => $questionnaireTitles[$index],
            'description' => $descriptions[$index],
            'order' => $index + 1,
            'is_required' => $index === 0, // Kuesioner 1 wajib
            'is_active' => $this->faker->boolean(95),
        ];
    }

    /**
     * Indicate that the questionnaire is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the questionnaire is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the questionnaire is required.
     */
    public function required(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Indicate that the questionnaire is optional.
     */
    public function optional(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => false,
        ]);
    }

    /**
     * Indicate a specific order for the questionnaire.
     */
    public function withOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'order' => $order,
        ]);
    }

    /**
     * Indicate a specific category for the questionnaire.
     */
    public function forCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    /**
     * Indicate questionnaire 1 (Umum).
     */
    public function questionnaire1(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Kuesioner 1 (Umum)',
            'title' => 'Data Diri & Pendidikan',
            'description' => 'Kuesioner tentang informasi pribadi dan riwayat pendidikan di UAD. Wajib diisi oleh seluruh alumni.',
            'order' => 1,
            'is_required' => true,
        ]);
    }

    /**
     * Indicate questionnaire 2.
     */
    public function questionnaire2(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Kuesioner 2',
            'title' => 'Pengalaman Kerja & Karir',
            'description' => 'Kuesioner tentang pengalaman kerja setelah lulus dari UAD.',
            'order' => 2,
            'is_required' => false,
        ]);
    }

    /**
     * Indicate questionnaire 3.
     */
    public function questionnaire3(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Kuesioner 3',
            'title' => 'Keterampilan & Kompetensi',
            'description' => 'Kuesioner tentang keterampilan yang diperoleh selama kuliah dan pengembangannya.',
            'order' => 3,
            'is_required' => false,
        ]);
    }

    /**
     * Indicate questionnaire 4.
     */
    public function questionnaire4(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Kuesioner 4',
            'title' => 'Kepuasan & Saran',
            'description' => 'Kuesioner tentang kepuasan terhadap pendidikan di UAD dan saran pengembangan.',
            'order' => 4,
            'is_required' => false,
        ]);
    }

    /**
     * Indicate a specific name for the questionnaire.
     */
    public function withName(string $name): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Indicate a specific title for the questionnaire.
     */
    public function withTitle(string $title): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => $title,
        ]);
    }
}
