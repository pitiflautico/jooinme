<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExternalLink>
 */
class ExternalLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'linkable_type' => \App\Models\Conversation::class,
            'linkable_id' => \App\Models\Conversation::factory(),
            'type' => fake()->randomElement(['zoom', 'google_meet', 'teams', 'whatsapp', 'telegram', 'discord', 'other']),
            'url' => fake()->url(),
            'title' => fake()->optional()->words(3, true),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
