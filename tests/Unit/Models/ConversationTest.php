<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\ExternalLink;
use App\Models\Feedback;
use App\Models\Message;
use App\Models\Participation;
use App\Models\ScheduleSlot;
use App\Models\Topic;
use App\Models\Transcription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversation_can_be_created(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();

        $conversation = Conversation::factory()->create([
            'owner_id' => $user->id,
            'topic_id' => $topic->id,
            'title' => 'Test Conversation',
        ]);

        $this->assertDatabaseHas('conversations', [
            'title' => 'Test Conversation',
            'owner_id' => $user->id,
            'topic_id' => $topic->id,
        ]);
    }

    public function test_conversation_auto_generates_slug(): void
    {
        $conversation = Conversation::factory()->create([
            'title' => 'Test Conversation',
            'slug' => null,
        ]);

        $this->assertNotNull($conversation->slug);
        $this->assertStringContainsString('test-conversation', $conversation->slug);
    }

    public function test_conversation_belongs_to_owner(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(User::class, $conversation->owner);
        $this->assertEquals($user->id, $conversation->owner->id);
    }

    public function test_conversation_belongs_to_topic(): void
    {
        $topic = Topic::factory()->create();
        $conversation = Conversation::factory()->create(['topic_id' => $topic->id]);

        $this->assertInstanceOf(Topic::class, $conversation->topic);
        $this->assertEquals($topic->id, $conversation->topic->id);
    }

    public function test_conversation_has_participations(): void
    {
        $conversation = Conversation::factory()->create();
        $participation = Participation::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($conversation->participations->contains($participation));
    }

    public function test_conversation_has_users_through_participations(): void
    {
        $conversation = Conversation::factory()->create();
        $user = User::factory()->create();
        Participation::factory()->create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($conversation->users->contains($user));
    }

    public function test_conversation_has_schedule_slots(): void
    {
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($conversation->scheduleSlots->contains($slot));
    }

    public function test_conversation_has_feedback(): void
    {
        $conversation = Conversation::factory()->create();
        $feedback = Feedback::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($conversation->feedback->contains($feedback));
    }

    public function test_conversation_has_external_links(): void
    {
        $conversation = Conversation::factory()->create();
        $link = ExternalLink::factory()->create([
            'linkable_id' => $conversation->id,
            'linkable_type' => Conversation::class,
        ]);

        $this->assertTrue($conversation->externalLinks->contains($link));
    }

    public function test_conversation_has_messages(): void
    {
        $conversation = Conversation::factory()->create();
        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($conversation->messages->contains($message));
    }

    public function test_conversation_has_transcriptions(): void
    {
        $conversation = Conversation::factory()->create();
        $transcription = Transcription::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertTrue($conversation->transcriptions->contains($transcription));
    }

    public function test_is_owner_method_works(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $conversation = Conversation::factory()->create(['owner_id' => $owner->id]);

        $this->assertTrue($conversation->isOwner($owner));
        $this->assertFalse($conversation->isOwner($otherUser));
    }

    public function test_is_member_method_works(): void
    {
        $conversation = Conversation::factory()->create();
        $member = User::factory()->create();
        $nonMember = User::factory()->create();

        Participation::factory()->create([
            'conversation_id' => $conversation->id,
            'user_id' => $member->id,
            'status' => 'accepted',
        ]);

        $this->assertTrue($conversation->fresh()->isMember($member));
        $this->assertFalse($conversation->fresh()->isMember($nonMember));
    }

    public function test_is_full_method_works(): void
    {
        $conversation = Conversation::factory()->create([
            'max_participants' => 2,
            'current_participants' => 1,
        ]);

        $this->assertFalse($conversation->isFull());

        $conversation->update(['current_participants' => 2]);

        $this->assertTrue($conversation->fresh()->isFull());
    }

    public function test_can_join_method_works_for_public_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'max_participants' => 10,
            'current_participants' => 5,
        ]);

        $this->assertTrue($conversation->canJoin($user));
    }

    public function test_can_join_method_returns_false_when_full(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'max_participants' => 2,
            'current_participants' => 2,
        ]);

        $this->assertFalse($conversation->canJoin($user));
    }

    public function test_can_join_method_returns_false_when_already_member(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create(['privacy' => 'public']);

        Participation::factory()->create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'status' => 'accepted',
        ]);

        $this->assertFalse($conversation->fresh()->canJoin($user));
    }

    public function test_average_rating_returns_correct_value(): void
    {
        $conversation = Conversation::factory()->create();

        Feedback::factory()->create([
            'conversation_id' => $conversation->id,
            'rating' => 5,
        ]);
        Feedback::factory()->create([
            'conversation_id' => $conversation->id,
            'rating' => 3,
        ]);

        $this->assertEquals(4.0, $conversation->averageRating());
    }

    public function test_conversation_soft_deletes(): void
    {
        $conversation = Conversation::factory()->create();
        $conversationId = $conversation->id;

        $conversation->delete();

        $this->assertSoftDeleted('conversations', ['id' => $conversationId]);
    }

    public function test_conversation_casts_attributes_correctly(): void
    {
        $conversation = new Conversation();
        $casts = $conversation->getCasts();

        $this->assertEquals('array', $casts['settings']);
        $this->assertEquals('array', $casts['tags']);
        $this->assertEquals('datetime', $casts['starts_at']);
        $this->assertEquals('datetime', $casts['ends_at']);
    }
}
