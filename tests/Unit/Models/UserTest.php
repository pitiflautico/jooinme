<?php

namespace Tests\Unit\Models;

use App\Models\Achievement;
use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\Mentorship;
use App\Models\Participation;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_has_fillable_attributes(): void
    {
        $fillable = [
            'name', 'email', 'password', 'avatar', 'bio', 'interests',
            'availability', 'preferences', 'location', 'latitude', 'longitude',
            'timezone', 'is_verified', 'is_active', 'last_active_at',
        ];

        $user = new User();
        $this->assertEquals($fillable, $user->getFillable());
    }

    public function test_user_casts_attributes_correctly(): void
    {
        $user = new User();
        $casts = $user->getCasts();

        $this->assertEquals('array', $casts['interests']);
        $this->assertEquals('array', $casts['availability']);
        $this->assertEquals('array', $casts['preferences']);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('datetime', $casts['last_active_at']);
    }

    public function test_user_has_owned_conversations_relationship(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create(['owner_id' => $user->id]);

        $this->assertTrue($user->ownedConversations->contains($conversation));
        $this->assertInstanceOf(Conversation::class, $user->ownedConversations->first());
    }

    public function test_user_has_participations_relationship(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($user->participations->contains($participation));
    }

    public function test_user_has_conversations_through_participations(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($user->conversations->contains($conversation));
    }

    public function test_user_can_give_feedback(): void
    {
        $user = User::factory()->create();
        $feedback = Feedback::factory()->create(['from_user_id' => $user->id]);

        $this->assertTrue($user->feedbackGiven->contains($feedback));
    }

    public function test_user_can_receive_feedback(): void
    {
        $user = User::factory()->create();
        $feedback = Feedback::factory()->create(['to_user_id' => $user->id]);

        $this->assertTrue($user->feedbackReceived->contains($feedback));
    }

    public function test_user_can_block_other_users(): void
    {
        $user = User::factory()->create();
        $blockedUser = User::factory()->create();

        $user->blocking()->attach($blockedUser->id);

        $this->assertTrue($user->blocking->contains($blockedUser));
    }

    public function test_user_can_follow_other_users(): void
    {
        $user = User::factory()->create();
        $followedUser = User::factory()->create();

        $user->following()->attach($followedUser->id);

        $this->assertTrue($user->following->contains($followedUser));
    }

    public function test_user_has_followers(): void
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $follower->following()->attach($user->id);

        $this->assertTrue($user->followers->contains($follower));
    }

    public function test_is_blocking_method_works(): void
    {
        $user = User::factory()->create();
        $blockedUser = User::factory()->create();

        $this->assertFalse($user->isBlocking($blockedUser));

        $user->blocking()->attach($blockedUser->id);

        $this->assertTrue($user->fresh()->isBlocking($blockedUser));
    }

    public function test_is_following_method_works(): void
    {
        $user = User::factory()->create();
        $followedUser = User::factory()->create();

        $this->assertFalse($user->isFollowing($followedUser));

        $user->following()->attach($followedUser->id);

        $this->assertTrue($user->fresh()->isFollowing($followedUser));
    }

    public function test_average_rating_returns_correct_value(): void
    {
        $user = User::factory()->create();

        Feedback::factory()->create(['to_user_id' => $user->id, 'rating' => 5]);
        Feedback::factory()->create(['to_user_id' => $user->id, 'rating' => 3]);

        $this->assertEquals(4.0, $user->averageRating());
    }

    public function test_average_rating_returns_zero_when_no_feedback(): void
    {
        $user = User::factory()->create();

        $this->assertEquals(0.0, $user->averageRating());
    }

    public function test_mark_as_online_updates_last_active_at(): void
    {
        $user = User::factory()->create(['last_active_at' => null]);

        $user->markAsOnline();

        $this->assertNotNull($user->fresh()->last_active_at);
    }

    public function test_user_has_achievements_relationship(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $user->achievements()->attach($achievement->id, [
            'unlocked_at' => now(),
        ]);

        $this->assertTrue($user->achievements->contains($achievement));
    }

    public function test_user_has_mentorships_as_mentor(): void
    {
        $user = User::factory()->create();
        $mentorship = Mentorship::factory()->create(['mentor_id' => $user->id]);

        $this->assertTrue($user->mentorshipsAsMentor->contains($mentorship));
    }

    public function test_user_has_mentorships_as_mentee(): void
    {
        $user = User::factory()->create();
        $mentorship = Mentorship::factory()->create(['mentee_id' => $user->id]);

        $this->assertTrue($user->mentorshipsAsMentee->contains($mentorship));
    }

    public function test_user_has_referrals_made(): void
    {
        $user = User::factory()->create();
        $referral = Referral::factory()->create(['referrer_id' => $user->id]);

        $this->assertTrue($user->referralsMade->contains($referral));
    }

    public function test_user_soft_deletes(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
        $this->assertNotNull(User::withTrashed()->find($userId)->deleted_at);
    }
}
