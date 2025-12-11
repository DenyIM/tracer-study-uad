<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['draft', 'submitted', 'pending_review', 'approved'];
        $status = $this->faker->randomElement($statuses);

        $totalQuestions = $this->faker->numberBetween(5, 20);
        $answeredQuestions = $status === 'draft'
            ? $this->faker->numberBetween(0, $totalQuestions)
            : $totalQuestions;

        $completionPercentage = ($answeredQuestions / $totalQuestions) * 100;

        return [
            'user_id' => User::factory(),
            'questionnaire_id' => Questionnaire::factory(),
            'category_id' => Category::factory(),
            'status' => $status,
            'submitted_at' => $status !== 'draft' ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'completion_percentage' => round($completionPercentage, 2),
        ];
    }

    /**
     * Indicate that the response is draft.
     */
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft',
            'submitted_at' => null,
            'answered_questions' => $this->faker->numberBetween(0, $attributes['total_questions']),
        ]);
    }

    /**
     * Indicate that the response is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'submitted',
            'submitted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'answered_questions' => $attributes['total_questions'],
            'completion_percentage' => 100,
        ]);
    }

    /**
     * Indicate that the response is pending review.
     */
    public function pendingReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending_review',
            'submitted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'answered_questions' => $attributes['total_questions'],
            'completion_percentage' => 100,
        ]);
    }

    /**
     * Indicate that the response is approved.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'approved',
            'submitted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'answered_questions' => $attributes['total_questions'],
            'completion_percentage' => 100,
        ]);
    }

    /**
     * Indicate a specific completion percentage.
     */
    public function withCompletionPercentage(float $percentage): static
    {
        $totalQuestions = $this->faker->numberBetween(5, 20);
        $answeredQuestions = round(($percentage / 100) * $totalQuestions);

        return $this->state(fn(array $attributes) => [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'completion_percentage' => $percentage,
        ]);
    }

    /**
     * Indicate a specific total questions count.
     */
    public function withTotalQuestions(int $count): static
    {
        return $this->state(fn(array $attributes) => [
            'total_questions' => $count,
        ]);
    }

    /**
     * Indicate a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate a specific questionnaire.
     */
    public function forQuestionnaire(Questionnaire $questionnaire): static
    {
        return $this->state(fn(array $attributes) => [
            'questionnaire_id' => $questionnaire->id,
            'category_id' => $questionnaire->category_id,
        ]);
    }

    /**
     * Indicate a specific category.
     */
    public function forCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    /**
     * Indicate that the response is completed (100%).
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'answered_questions' => $attributes['total_questions'],
            'completion_percentage' => 100,
        ]);
    }

    /**
     * Indicate that the response is in progress (50-99%).
     */
    public function inProgress(): static
    {
        $percentage = $this->faker->numberBetween(50, 99);
        $totalQuestions = $this->faker->numberBetween(5, 20);
        $answeredQuestions = round(($percentage / 100) * $totalQuestions);

        return $this->state(fn(array $attributes) => [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'completion_percentage' => $percentage,
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the response is just started (1-49%).
     */
    public function justStarted(): static
    {
        $percentage = $this->faker->numberBetween(1, 49);
        $totalQuestions = $this->faker->numberBetween(5, 20);
        $answeredQuestions = round(($percentage / 100) * $totalQuestions);

        return $this->state(fn(array $attributes) => [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answeredQuestions,
            'completion_percentage' => $percentage,
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the response is not started (0%).
     */
    public function notStarted(): static
    {
        return $this->state(fn(array $attributes) => [
            'answered_questions' => 0,
            'completion_percentage' => 0,
            'status' => 'draft',
        ]);
    }
}
