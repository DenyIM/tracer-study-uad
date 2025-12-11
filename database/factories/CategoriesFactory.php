<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            [
                'name' => 'BEKERJA DI PERUSAHAAN/INSTANSI',
                'icon' => 'fas fa-building',
                'description' => 'Alumni yang sedang bekerja di perusahaan, instansi pemerintah, atau organisasi lainnya.',
            ],
            [
                'name' => 'WIRAUSAHA/PEMILIK USAHA',
                'icon' => 'fas fa-briefcase',
                'description' => 'Alumni yang memiliki usaha sendiri atau berwirausaha.',
            ],
            [
                'name' => 'MELANJUTKAN PENDIDIKAN',
                'icon' => 'fas fa-graduation-cap',
                'description' => 'Alumni yang sedang melanjutkan pendidikan ke jenjang yang lebih tinggi.',
            ],
            [
                'name' => 'PENCARI KERJA',
                'icon' => 'fas fa-search',
                'description' => 'Alumni yang sedang mencari pekerjaan atau belum mendapatkan pekerjaan.',
            ],
            [
                'name' => 'TIDAK BEKERJA & TIDAK MENCARI',
                'icon' => 'fas fa-user-clock',
                'description' => 'Alumni yang tidak bekerja dan tidak sedang mencari pekerjaan.',
            ],
        ];

        $category = $this->faker->randomElement($categories);

        return [
            'name' => $category['name'],
            'icon' => $category['icon'],
            'description' => $category['description'],
            'is_active' => $this->faker->boolean(90), // 90% chance aktif
        ];
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate the category for "Bekerja di Perusahaan/Instansi".
     */
    public function bekerja(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'BEKERJA DI PERUSAHAAN/INSTANSI',
            'icon' => 'fas fa-building',
            'description' => 'Alumni yang sedang bekerja di perusahaan, instansi pemerintah, atau organisasi lainnya.',
        ]);
    }

    /**
     * Indicate the category for "Wirausaha/Pemilik Usaha".
     */
    public function wirausaha(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'WIRAUSAHA/PEMILIK USAHA',
            'icon' => 'fas fa-briefcase',
            'description' => 'Alumni yang memiliki usaha sendiri atau berwirausaha.',
        ]);
    }

    /**
     * Indicate the category for "Melanjutkan Pendidikan".
     */
    public function pendidikan(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'MELANJUTKAN PENDIDIKAN',
            'icon' => 'fas fa-graduation-cap',
            'description' => 'Alumni yang sedang melanjutkan pendidikan ke jenjang yang lebih tinggi.',
        ]);
    }

    /**
     * Indicate the category for "Pencari Kerja".
     */
    public function pencari(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'PENCARI KERJA',
            'icon' => 'fas fa-search',
            'description' => 'Alumni yang sedang mencari pekerjaan atau belum mendapatkan pekerjaan.',
        ]);
    }

    /**
     * Indicate the category for "Tidak Bekerja & Tidak Mencari".
     */
    public function tidakKerja(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'TIDAK BEKERJA & TIDAK MENCARI',
            'icon' => 'fas fa-user-clock',
            'description' => 'Alumni yang tidak bekerja dan tidak sedang mencari pekerjaan.',
        ]);
    }

    /**
     * Indicate that the category has a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Indicate that the category has a specific icon.
     */
    public function withIcon(string $icon): static
    {
        return $this->state(fn(array $attributes) => [
            'icon' => $icon,
        ]);
    }
}
