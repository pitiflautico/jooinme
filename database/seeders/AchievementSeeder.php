<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // Bronze Achievements
            [
                'name' => 'First Conversation',
                'slug' => 'first-conversation',
                'description' => 'Join your first conversation',
                'icon' => '🎉',
                'type' => 'bronze',
                'points' => 10,
                'criteria' => ['type' => 'conversations_joined', 'count' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'First Host',
                'slug' => 'first-host',
                'description' => 'Host your first conversation',
                'icon' => '🎤',
                'type' => 'bronze',
                'points' => 15,
                'criteria' => ['type' => 'conversations_created', 'count' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'Early Bird',
                'slug' => 'early-bird',
                'description' => 'Attend 5 conversations',
                'icon' => '🐦',
                'type' => 'bronze',
                'points' => 20,
                'criteria' => ['type' => 'attendances', 'count' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Feedback Friend',
                'slug' => 'feedback-friend',
                'description' => 'Leave your first feedback',
                'icon' => '💬',
                'type' => 'bronze',
                'points' => 10,
                'criteria' => ['type' => 'feedback_given', 'count' => 1],
                'is_active' => true,
            ],

            // Silver Achievements
            [
                'name' => 'Active Participant',
                'slug' => 'active-participant',
                'description' => 'Join 10 conversations',
                'icon' => '⚡',
                'type' => 'silver',
                'points' => 30,
                'criteria' => ['type' => 'conversations_joined', 'count' => 10],
                'is_active' => true,
            ],
            [
                'name' => 'Regular Host',
                'slug' => 'regular-host',
                'description' => 'Host 5 conversations',
                'icon' => '🎪',
                'type' => 'silver',
                'points' => 40,
                'criteria' => ['type' => 'conversations_created', 'count' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Committed Member',
                'slug' => 'committed-member',
                'description' => 'Attend 20 conversations',
                'icon' => '🏆',
                'type' => 'silver',
                'points' => 50,
                'criteria' => ['type' => 'attendances', 'count' => 20],
                'is_active' => true,
            ],
            [
                'name' => 'Helpful Reviewer',
                'slug' => 'helpful-reviewer',
                'description' => 'Leave 10 feedbacks',
                'icon' => '✨',
                'type' => 'silver',
                'points' => 35,
                'criteria' => ['type' => 'feedback_given', 'count' => 10],
                'is_active' => true,
            ],

            // Gold Achievements
            [
                'name' => 'Super Participant',
                'slug' => 'super-participant',
                'description' => 'Join 50 conversations',
                'icon' => '🌟',
                'type' => 'gold',
                'points' => 70,
                'criteria' => ['type' => 'conversations_joined', 'count' => 50],
                'is_active' => true,
            ],
            [
                'name' => 'Master Host',
                'slug' => 'master-host',
                'description' => 'Host 20 conversations',
                'icon' => '👑',
                'type' => 'gold',
                'points' => 80,
                'criteria' => ['type' => 'conversations_created', 'count' => 20],
                'is_active' => true,
            ],
            [
                'name' => 'Dedicated Member',
                'slug' => 'dedicated-member',
                'description' => 'Attend 50 conversations',
                'icon' => '💎',
                'type' => 'gold',
                'points' => 90,
                'criteria' => ['type' => 'attendances', 'count' => 50],
                'is_active' => true,
            ],
            [
                'name' => 'Five Star Reviewer',
                'slug' => 'five-star-reviewer',
                'description' => 'Maintain 5-star average rating',
                'icon' => '⭐',
                'type' => 'gold',
                'points' => 75,
                'criteria' => ['type' => 'average_rating', 'min' => 5.0],
                'is_active' => true,
            ],

            // Platinum Achievements
            [
                'name' => 'Legend',
                'slug' => 'legend',
                'description' => 'Join 100 conversations',
                'icon' => '🔥',
                'type' => 'platinum',
                'points' => 150,
                'criteria' => ['type' => 'conversations_joined', 'count' => 100],
                'is_active' => true,
            ],
            [
                'name' => 'Elite Host',
                'slug' => 'elite-host',
                'description' => 'Host 50 conversations',
                'icon' => '👑',
                'type' => 'platinum',
                'points' => 200,
                'criteria' => ['type' => 'conversations_created', 'count' => 50],
                'is_active' => true,
            ],
            [
                'name' => 'Community Builder',
                'slug' => 'community-builder',
                'description' => 'Help 100 people through mentorship',
                'icon' => '🤝',
                'type' => 'platinum',
                'points' => 250,
                'criteria' => ['type' => 'mentorships_completed', 'count' => 100],
                'is_active' => true,
            ],
            [
                'name' => 'Influencer',
                'slug' => 'influencer',
                'description' => 'Get 50 referrals',
                'icon' => '📢',
                'type' => 'platinum',
                'points' => 300,
                'criteria' => ['type' => 'referrals_accepted', 'count' => 50],
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            \App\Models\Achievement::create($achievement);
        }
    }
}
