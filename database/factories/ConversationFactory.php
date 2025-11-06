<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => \App\Models\User::factory(),
            'topic_id' => \App\Models\Topic::factory(),
            'title' => fake()->sentence(4),
            'slug' => null, // Will be auto-generated
            'description' => fake()->paragraph(),
            'frequency' => fake()->randomElement(['once', 'daily', 'weekly', 'biweekly', 'monthly']),
            'type' => fake()->randomElement(['online', 'in_person', 'hybrid']),
            'privacy' => fake()->randomElement(['public', 'moderated', 'private']),
            'max_participants' => fake()->numberBetween(5, 50),
            'current_participants' => 0,
            'location' => fake()->optional()->city(),
            'latitude' => fake()->optional()->latitude(),
            'longitude' => fake()->optional()->longitude(),
            'meeting_url' => fake()->optional()->url(),
            'meeting_platform' => fake()->optional()->randomElement(['zoom', 'google_meet', 'teams']),
            'starts_at' => fake()->dateTimeBetween('now', '+1 month'),
            'ends_at' => fake()->optional()->dateTimeBetween('+1 month', '+6 months'),
            'allow_chat' => true,
            'allow_recording' => fake()->boolean(),
            'auto_confirm' => fake()->boolean(),
            'require_approval' => fake()->boolean(),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'settings' => [
                'notifications_enabled' => true,
                'max_message_length' => 500,
            ],
            'tags' => fake()->optional()->words(3),
        ];
    }
}
