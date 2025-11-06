<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_user_id' => \App\Models\User::factory(),
            'to_user_id' => \App\Models\User::factory(),
            'conversation_id' => \App\Models\Conversation::factory(),
            'schedule_slot_id' => \App\Models\ScheduleSlot::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional()->paragraph(),
        ];
    }
}
