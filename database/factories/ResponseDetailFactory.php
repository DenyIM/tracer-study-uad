<?php

namespace Database\Factories;

use App\Models\UserResponse;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponseDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = Question::factory()->create();

        // Generate answer based on question type
        $answer = $this->generateAnswerForQuestion($question);

        return [
            'user_response_id' => UserResponse::factory(),
            'question_id' => $question->id,
            'answer_text' => $answer['text'] ?? null,
            'answer_value' => $answer['value'] ?? null,
            'other_answer' => $answer['other'] ?? null,
            'matrix_answers' => $answer['matrix'] ?? null,
        ];
    }

    /**
     * Generate answer based on question type.
     */
    protected function generateAnswerForQuestion(Question $question): array
    {
        $answer = [];

        switch ($question->type) {
            case 'text':
            case 'textarea':
                $answer['text'] = $this->faker->paragraph();
                break;

            case 'dropdown':
            case 'radio':
                // For choice questions, select random option value
                if ($question->options && $question->options->count() > 0) {
                    $option = $question->options->random();
                    $answer['value'] = $option->value ?? $this->faker->numberBetween(1, 5);

                    // 10% chance for "other" option
                    if ($question->has_other_option && $this->faker->boolean(10)) {
                        $answer['value'] = null;
                        $answer['other'] = $this->faker->sentence();
                    }
                } else {
                    $answer['value'] = $this->faker->numberBetween(1, 5);
                }
                break;

            case 'checkbox':
                // For checkbox, store as text with comma separated
                $selections = [];
                if ($question->options && $question->options->count() > 0) {
                    $count = $this->faker->numberBetween(1, min(3, $question->options->count()));
                    $selectedOptions = $question->options->random($count);

                    foreach ($selectedOptions as $option) {
                        $selections[] = $option->option_text;
                    }
                }
                $answer['text'] = implode(', ', $selections);
                break;

            case 'number':
                $answer['value'] = $this->faker->numberBetween(1, 100);
                break;

            case 'scale':
                $answer['value'] = $this->faker->numberBetween(1, 5);
                break;

            case 'competency_scale':
            case 'matrix':
                // Generate matrix answers
                $competencies = ['Etika', 'Keahlian Bidang Ilmu', 'Bahasa Inggris', 'Penggunaan IT', 'Komunikasi'];
                $matrix = [];
                foreach ($competencies as $competency) {
                    $matrix[$competency] = $this->faker->numberBetween(1, 5);
                }
                $answer['matrix'] = $matrix;
                break;

            case 'date':
                $answer['text'] = $this->faker->date();
                break;

            default:
                $answer['text'] = $this->faker->sentence();
        }

        return $answer;
    }

    /**
     * Indicate a text answer.
     */
    public function textAnswer(): static
    {
        return $this->state(fn(array $attributes) => [
            'answer_text' => $this->faker->paragraph(),
            'answer_value' => null,
            'other_answer' => null,
            'matrix_answers' => null,
        ]);
    }

    /**
     * Indicate a numeric answer.
     */
    public function numericAnswer(int $value): static
    {
        return $this->state(fn(array $attributes) => [
            'answer_text' => null,
            'answer_value' => $value,
            'other_answer' => null,
            'matrix_answers' => null,
        ]);
    }

    /**
     * Indicate a scale answer (1-5).
     */
    public function scaleAnswer(): static
    {
        return $this->state(fn(array $attributes) => [
            'answer_text' => null,
            'answer_value' => $this->faker->numberBetween(1, 5),
            'other_answer' => null,
            'matrix_answers' => null,
        ]);
    }

    /**
     * Indicate an "other" answer.
     */
    public function otherAnswer(): static
    {
        return $this->state(fn(array $attributes) => [
            'answer_text' => null,
            'answer_value' => null,
            'other_answer' => $this->faker->sentence(),
            'matrix_answers' => null,
        ]);
    }

    /**
     * Indicate a matrix answer.
     */
    public function matrixAnswer(): static
    {
        $competencies = ['Etika', 'Keahlian Bidang Ilmu', 'Bahasa Inggris', 'Penggunaan IT', 'Komunikasi'];
        $matrix = [];
        foreach ($competencies as $competency) {
            $matrix[$competency] = $this->faker->numberBetween(1, 5);
        }

        return $this->state(fn(array $attributes) => [
            'answer_text' => null,
            'answer_value' => null,
            'other_answer' => null,
            'matrix_answers' => $matrix,
        ]);
    }

    /**
     * Indicate a specific user response.
     */
    public function forUserResponse(UserResponse $userResponse): static
    {
        return $this->state(fn(array $attributes) => [
            'user_response_id' => $userResponse->id,
        ]);
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
     * Indicate no answer (empty response).
     */
    public function noAnswer(): static
    {
        return $this->state(fn(array $attributes) => [
            'answer_text' => null,
            'answer_value' => null,
            'other_answer' => null,
            'matrix_answers' => null,
        ]);
    }
}
