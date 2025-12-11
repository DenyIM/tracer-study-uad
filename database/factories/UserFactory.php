<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $programStudi = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen',
            'Akuntansi',
            'Psikologi',
            'Kedokteran',
            'Farmasi',
            'Hukum',
            'Ilmu Komunikasi',
        ];

        return [
            'nama_lengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'nim' => 'UAD' . $this->faker->unique()->numberBetween(100000, 999999),
            'program_studi' => $this->faker->randomElement($programStudi),
            'tanggal_lulus' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'npwp' => $this->faker->optional(0.7)->numerify('##.###.###.#-###.###'),
            'no_hp' => '628' . $this->faker->numerify('##########'),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // password default untuk testing
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
