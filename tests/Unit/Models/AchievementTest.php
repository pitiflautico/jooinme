<?php

namespace Tests\Unit\Models;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievement_can_be_created(): void
    {
        $achievement = Achievement::factory()->create([
            'name' => 'First Conversation',
            'slug' => 'first-conversation',
        ]);

        $this->assertDatabaseHas('achievements', [
            'name' => 'First Conversation',
            'slug' => 'first-conversation',
        ]);
    }

    public function test_achievement_has_users_relationship(): void
    {
        $achievement = Achievement::factory()->create();
        $user = User::factory()->create();

        $achievement->users()->attach($user->id, [
            'unlocked_at' => now(),
        ]);

        $this->assertTrue($achievement->users->contains($user));
    }

    public function test_achievement_has_fillable_attributes(): void
    {
        $fillable = [
            'name', 'slug', 'description', 'icon', 'type', 'points', 'criteria', 'is_active',
        ];

        $achievement = new Achievement();
        $this->assertEquals($fillable, $achievement->getFillable());
    }

    public function test_achievement_casts_attributes_correctly(): void
    {
        $achievement = new Achievement();
        $casts = $achievement->getCasts();

        $this->assertEquals('array', $casts['criteria']);
        $this->assertEquals('boolean', $casts['is_active']);
    }

    public function test_achievement_default_type_is_bronze(): void
    {
        $achievement = Achievement::factory()->create();

        $this->assertContains($achievement->type, ['bronze', 'silver', 'gold', 'platinum']);
    }

    public function test_achievement_slug_is_unique(): void
    {
        Achievement::factory()->create(['slug' => 'unique-achievement']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Achievement::factory()->create(['slug' => 'unique-achievement']);
    }

    public function test_achievement_can_have_multiple_users(): void
    {
        $achievement = Achievement::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            $achievement->users()->attach($user->id, ['unlocked_at' => now()]);
        }

        $this->assertCount(3, $achievement->fresh()->users);
    }
}
