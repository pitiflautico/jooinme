<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'icon' => fake()->optional()->word(),
            'awarded_at' => now(),
        ];
    }
}
