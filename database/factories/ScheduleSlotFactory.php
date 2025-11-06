<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleSlot>
 */
class ScheduleSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduledAt = fake()->dateTimeBetween('now', '+1 month');
        $endsAt = (clone $scheduledAt)->modify('+1 hour');

        return [
            'conversation_id' => \App\Models\Conversation::factory(),
            'scheduled_at' => $scheduledAt,
            'ends_at' => $endsAt,
            'status' => 'scheduled',
            'confirmed_participants' => 0,
            'attended_participants' => 0,
            'meeting_url' => fake()->optional()->url(),
            'recording_url' => fake()->optional()->url(),
            'notes' => fake()->optional()->paragraph(),
            'metadata' => [
                'platform' => fake()->randomElement(['zoom', 'google_meet', 'teams']),
                'duration_minutes' => 60,
            ],
        ];
    }
}
