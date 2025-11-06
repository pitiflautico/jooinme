<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
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
            'conversation_id' => \App\Models\Conversation::factory(),
            'content' => fake()->sentence(),
            'type' => 'text',
            'is_edited' => false,
            'edited_at' => null,
        ];
    }
}
