<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->userName(),
            'phone' => '628' . $this->faker->numerify('##########'),
            'role' => $this->faker->randomElement(['admin', 'operator']),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function operator(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'operator',
        ]);
    }
}
