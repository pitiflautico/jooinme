<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding database...');

        // 1. Create users first
        $this->command->info('Creating users...');
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@joinme.app',
            'is_verified' => true,
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@joinme.app',
            'is_verified' => true,
            'is_active' => true,
        ]);

        $this->command->info('✓ Created ' . User::count() . ' users');

        // 2. Seed topics
        $this->command->info('Creating topics...');
        $this->call(TopicSeeder::class);
        $this->command->info('✓ Created topics');

        // 3. Seed achievements
        $this->command->info('Creating achievements...');
        $this->call(AchievementSeeder::class);
        $this->command->info('✓ Created achievements');

        // 4. Seed conversations with participants and schedule slots
        $this->command->info('Creating conversations...');
        $this->call(ConversationSeeder::class);

        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('You can now login with:');
        $this->command->info('  Email: demo@joinme.app');
        $this->command->info('  Password: password');
    }
}
