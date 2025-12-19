<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlumniFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'nim' => $this->faker->unique()->numerify('##########'),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', '-20 years'),
            'phone' => $this->faker->phoneNumber(),
            'study_program' => $this->faker->randomElement([
                'Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Teknik Mesin',
                'Akuntansi',
                'Manajemen'
            ]),
            'graduation_date' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'npwp' => $this->faker->optional()->numerify('##.###.###.#-###.###'),
        ];
    }
}
