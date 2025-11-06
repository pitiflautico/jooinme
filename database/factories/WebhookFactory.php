<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Webhook>
 */
class WebhookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'url' => fake()->url(),
            'events' => ['conversation.created', 'participation.joined'],
            'secret' => fake()->sha256(),
            'retry_count' => 3,
            'status' => 'active',
            'last_triggered_at' => null,
        ];
    }
}
