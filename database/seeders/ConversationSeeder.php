<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all topics and users
        $topics = \App\Models\Topic::all();
        $users = \App\Models\User::all();

        if ($topics->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please seed Topics and Users first.');
            return;
        }

        // Create 20 diverse conversations
        foreach (range(1, 20) as $index) {
            $topic = $topics->random();
            $owner = $users->random();

            $conversation = \App\Models\Conversation::create([
                'owner_id' => $owner->id,
                'topic_id' => $topic->id,
                'title' => $this->getConversationTitle($topic->name, $index),
                'description' => $this->getConversationDescription($topic->name),
                'frequency' => collect(['weekly', 'biweekly', 'monthly'])->random(),
                'type' => collect(['online', 'in_person', 'hybrid'])->random(),
                'privacy' => collect(['public', 'moderated', 'private'])->random(1, [
                    'public' => 70,
                    'moderated' => 20,
                    'private' => 10,
                ])->first(),
                'max_participants' => rand(5, 30),
                'current_participants' => 0,
                'meeting_platform' => collect(['zoom', 'google_meet', 'teams'])->random(),
                'starts_at' => now()->addDays(rand(1, 30)),
                'ends_at' => now()->addMonths(rand(1, 6)),
                'allow_chat' => rand(0, 1),
                'allow_recording' => rand(0, 1),
                'auto_confirm' => rand(0, 1),
                'require_approval' => rand(0, 1),
                'is_active' => true,
                'is_featured' => rand(0, 100) < 20,
                'settings' => [
                    'notifications_enabled' => true,
                    'max_message_length' => 500,
                ],
                'tags' => $this->getTags($topic->category),
            ]);

            // Add some participants to each conversation
            $participantCount = rand(2, min(10, $conversation->max_participants));
            $participants = $users->except($owner->id)->random($participantCount);

            foreach ($participants as $participant) {
                \App\Models\Participation::create([
                    'user_id' => $participant->id,
                    'conversation_id' => $conversation->id,
                    'status' => collect(['pending', 'accepted'])->random(1, [
                        'pending' => 20,
                        'accepted' => 80,
                    ])->first(),
                    'role' => 'participant',
                    'joined_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Update participant count
            $conversation->update([
                'current_participants' => $participantCount + 1, // +1 for owner
            ]);

            // Add some schedule slots
            $this->createScheduleSlots($conversation);
        }

        $this->command->info('Created 20 conversations with participants and schedule slots.');
    }

    private function getConversationTitle(string $topicName, int $index): string
    {
        $prefixes = ['Weekly', 'Monthly', 'Regular', 'Community', 'Interactive'];
        $suffixes = ['Meetup', 'Discussion', 'Gathering', 'Session', 'Circle'];

        return "{$prefixes[array_rand($prefixes)]} {$topicName} {$suffixes[array_rand($suffixes)]}";
    }

    private function getConversationDescription(string $topicName): string
    {
        $descriptions = [
            "Join us for engaging discussions about {$topicName}. Share your insights and learn from others.",
            "A community-driven conversation about {$topicName}. All skill levels welcome!",
            "Connect with like-minded individuals interested in {$topicName}.",
            "Explore {$topicName} topics through interactive conversations and networking.",
            "Regular meetup for {$topicName} enthusiasts. Share experiences and grow together.",
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getTags(string $category): array
    {
        $tagMap = [
            'Technology' => ['coding', 'software', 'innovation', 'tech'],
            'Business' => ['startups', 'strategy', 'growth', 'leadership'],
            'Creative' => ['design', 'art', 'creative', 'visual'],
            'Health' => ['wellness', 'fitness', 'mental-health', 'nutrition'],
            'Education' => ['learning', 'teaching', 'skills', 'knowledge'],
            'Science' => ['research', 'innovation', 'discovery', 'experiments'],
            'Culture' => ['arts', 'music', 'literature', 'culture'],
            'Self-Improvement' => ['growth', 'productivity', 'habits', 'mindset'],
            'Finance' => ['investing', 'savings', 'wealth', 'money'],
            'Environment' => ['sustainability', 'climate', 'eco-friendly', 'green'],
        ];

        $tags = $tagMap[$category] ?? ['general', 'community', 'discussion'];

        return array_slice($tags, 0, rand(2, 4));
    }

    private function createScheduleSlots(\App\Models\Conversation $conversation): void
    {
        $slotCount = rand(2, 5);
        $startDate = $conversation->starts_at;

        foreach (range(1, $slotCount) as $index) {
            $scheduledAt = (clone $startDate)->modify("+{$index} week");
            $endsAt = (clone $scheduledAt)->modify('+1 hour');

            \App\Models\ScheduleSlot::create([
                'conversation_id' => $conversation->id,
                'scheduled_at' => $scheduledAt,
                'ends_at' => $endsAt,
                'status' => $index === 1 ? 'scheduled' : collect(['scheduled', 'completed'])->random(),
                'confirmed_participants' => rand(0, $conversation->current_participants),
                'attended_participants' => 0,
                'meeting_url' => "https://meet.example.com/{$conversation->id}-{$index}",
                'metadata' => [
                    'platform' => $conversation->meeting_platform,
                    'duration_minutes' => 60,
                ],
            ]);
        }
    }
}
