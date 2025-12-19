<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'job_title' => $this->faker->randomElement([
                'Administrator',
                'Super Admin',
                'Koordinator',
                'Staff Administrasi'
            ]),
        ];
    }
}
