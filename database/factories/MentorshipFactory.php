<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentorship>
 */
class MentorshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mentor_id' => \App\Models\User::factory(),
            'mentee_id' => \App\Models\User::factory(),
            'topic' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'status' => 'pending',
            'price' => fake()->optional()->randomFloat(2, 10, 200),
            'currency' => 'USD',
            'duration_minutes' => 60,
            'scheduled_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'started_at' => null,
            'completed_at' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
