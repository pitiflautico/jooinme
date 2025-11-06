<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
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
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 1000),
            'category' => fake()->optional()->randomElement(['Technology', 'Business', 'Health', 'Education', 'Entertainment']),
            'description' => fake()->optional()->sentence(),
            'icon' => fake()->optional()->randomElement(['💻', '📚', '🎨', '🔬', '🎯']),
            'color' => fake()->optional()->hexColor(),
            'is_active' => true,
        ];
    }
}
