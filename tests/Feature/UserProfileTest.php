<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_user_can_view_other_user_profile(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'name' => 'John Doe',
            'bio' => 'Software Engineer',
        ]);

        $response = $this->actingAs($user)->get("/users/{$otherUser->id}");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Software Engineer');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Updated Name',
            'bio' => 'Updated bio',
            'location' => 'San Francisco',
            'timezone' => 'America/Los_Angeles',
            'interests' => ['technology', 'design', 'startups'],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'bio' => 'Updated bio',
            'location' => 'San Francisco',
        ]);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->post('/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertStatus(302);
        $this->assertNotNull($user->fresh()->avatar);
        Storage::disk('public')->assertExists($user->fresh()->avatar);
    }

    public function test_user_can_update_availability(): void
    {
        $user = User::factory()->create();

        $availability = [
            'monday' => ['09:00', '17:00'],
            'wednesday' => ['14:00', '18:00'],
            'friday' => ['10:00', '16:00'],
        ];

        $response = $this->actingAs($user)->put('/profile/availability', [
            'availability' => $availability,
        ]);

        $response->assertStatus(302);
        $this->assertEquals($availability, $user->fresh()->availability);
    }

    public function test_user_can_view_own_conversations(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/conversations');

        $response->assertStatus(200);
        $response->assertViewHas('ownedConversations');
        $response->assertViewHas('participatingConversations');
    }

    public function test_user_can_view_achievements(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile/achievements');

        $response->assertStatus(200);
        $response->assertViewHas('achievements');
    }

    public function test_user_profile_shows_statistics(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewHas('stats');
    }

    public function test_user_can_update_preferences(): void
    {
        $user = User::factory()->create();

        $preferences = [
            'email_notifications' => true,
            'push_notifications' => false,
            'weekly_digest' => true,
            'language' => 'en',
            'theme' => 'dark',
        ];

        $response = $this->actingAs($user)->put('/profile/preferences', [
            'preferences' => $preferences,
        ]);

        $response->assertStatus(302);
        $this->assertEquals($preferences, $user->fresh()->preferences);
    }
}
