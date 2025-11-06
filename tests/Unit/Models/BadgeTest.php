<?php

namespace Tests\Unit\Models;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_badge_can_be_created(): void
    {
        $badge = Badge::factory()->create([
            'name' => 'Super Participant',
            'slug' => 'super-participant',
        ]);

        $this->assertDatabaseHas('badges', [
            'name' => 'Super Participant',
            'slug' => 'super-participant',
        ]);
    }

    public function test_badge_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $badge = Badge::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $badge->user);
        $this->assertEquals($user->id, $badge->user->id);
    }

    public function test_badge_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'name', 'slug', 'description', 'icon', 'awarded_at',
        ];

        $badge = new Badge();
        $this->assertEquals($fillable, $badge->getFillable());
    }

    public function test_badge_casts_attributes_correctly(): void
    {
        $badge = new Badge();
        $casts = $badge->getCasts();

        $this->assertEquals('datetime', $casts['awarded_at']);
    }

    public function test_badge_auto_sets_awarded_at_on_creation(): void
    {
        $badge = Badge::factory()->create();

        $this->assertNotNull($badge->awarded_at);
    }
}
