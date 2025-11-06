<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'icon' => fake()->optional()->word(),
            'type' => fake()->randomElement(['bronze', 'silver', 'gold', 'platinum']),
            'points' => fake()->numberBetween(10, 100),
            'criteria' => [
                'type' => fake()->randomElement(['conversations', 'attendances', 'feedback']),
                'count' => fake()->numberBetween(1, 50),
            ],
            'is_active' => true,
        ];
    }
}
