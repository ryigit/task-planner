<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => fake()->numberBetween(1, 10),
            'source_id' => fake()->numberBetween(1, 10),
            'complexity' => fake()->numberBetween(1, 5),
            'duration' => fake()->numberBetween(1, 10),
        ];
    }
}
