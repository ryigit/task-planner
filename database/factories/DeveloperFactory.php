<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'efficiency_rate' => fake()->numberBetween(1, 10),
            'weekly_hours' => fake()->numberBetween(30, 50),
            'is_active' => fake()->boolean,
        ];
    }
}
