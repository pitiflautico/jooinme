<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Technology & Programming',
                'slug' => 'technology-programming',
                'category' => 'Technology',
                'description' => 'Discuss latest tech trends, programming languages, and software development',
                'icon' => '💻',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Business & Entrepreneurship',
                'slug' => 'business-entrepreneurship',
                'category' => 'Business',
                'description' => 'Share insights on startups, business strategies, and entrepreneurship',
                'icon' => '💼',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Design & Creativity',
                'slug' => 'design-creativity',
                'category' => 'Creative',
                'description' => 'Explore UI/UX design, graphic design, and creative processes',
                'icon' => '🎨',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'category' => 'Health',
                'description' => 'Discussions about physical and mental health, fitness, and well-being',
                'icon' => '🏥',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Education & Learning',
                'slug' => 'education-learning',
                'category' => 'Education',
                'description' => 'Share knowledge, learning resources, and educational experiences',
                'icon' => '📚',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Science & Research',
                'slug' => 'science-research',
                'category' => 'Science',
                'description' => 'Explore scientific discoveries, research methodologies, and innovations',
                'icon' => '🔬',
                'color' => '#06B6D4',
                'is_active' => true,
            ],
            [
                'name' => 'Arts & Culture',
                'slug' => 'arts-culture',
                'category' => 'Culture',
                'description' => 'Discuss music, literature, film, and cultural experiences',
                'icon' => '🎭',
                'color' => '#EC4899',
                'is_active' => true,
            ],
            [
                'name' => 'Personal Development',
                'slug' => 'personal-development',
                'category' => 'Self-Improvement',
                'description' => 'Growth mindset, productivity, habits, and self-improvement',
                'icon' => '🎯',
                'color' => '#14B8A6',
                'is_active' => true,
            ],
            [
                'name' => 'Finance & Investing',
                'slug' => 'finance-investing',
                'category' => 'Finance',
                'description' => 'Personal finance, investing strategies, and wealth building',
                'icon' => '💰',
                'color' => '#F97316',
                'is_active' => true,
            ],
            [
                'name' => 'Environment & Sustainability',
                'slug' => 'environment-sustainability',
                'category' => 'Environment',
                'description' => 'Climate change, sustainability practices, and environmental issues',
                'icon' => '🌱',
                'color' => '#22C55E',
                'is_active' => true,
            ],
        ];

        foreach ($topics as $topic) {
            \App\Models\Topic::create($topic);
        }
    }
}
