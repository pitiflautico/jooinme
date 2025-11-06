<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transcription>
 */
class TranscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => \App\Models\Conversation::factory(),
            'schedule_slot_id' => \App\Models\ScheduleSlot::factory(),
            'content' => fake()->paragraphs(5, true),
            'summary' => fake()->optional()->paragraphs(2, true),
            'key_points' => fake()->optional()->randomElements([
                'Introduction and context',
                'Main discussion points',
                'Action items',
                'Conclusions',
            ], fake()->numberBetween(2, 4)),
            'status' => 'processing',
            'processed_at' => null,
        ];
    }
}
