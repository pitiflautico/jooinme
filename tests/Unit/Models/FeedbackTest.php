<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\ScheduleSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_feedback_can_be_created(): void
    {
        $fromUser = User::factory()->create();
        $toUser = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $feedback = Feedback::factory()->create([
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'conversation_id' => $conversation->id,
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('feedback', [
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'rating' => 5,
        ]);
    }

    public function test_feedback_belongs_to_from_user(): void
    {
        $user = User::factory()->create();
        $feedback = Feedback::factory()->create(['from_user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $feedback->fromUser);
        $this->assertEquals($user->id, $feedback->fromUser->id);
    }

    public function test_feedback_belongs_to_to_user(): void
    {
        $user = User::factory()->create();
        $feedback = Feedback::factory()->create(['to_user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $feedback->toUser);
        $this->assertEquals($user->id, $feedback->toUser->id);
    }

    public function test_feedback_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $feedback = Feedback::factory()->create(['conversation_id' => $conversation->id]);

        $this->assertInstanceOf(Conversation::class, $feedback->conversation);
        $this->assertEquals($conversation->id, $feedback->conversation->id);
    }

    public function test_feedback_belongs_to_schedule_slot(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $feedback = Feedback::factory()->create(['schedule_slot_id' => $slot->id]);

        $this->assertInstanceOf(ScheduleSlot::class, $feedback->scheduleSlot);
        $this->assertEquals($slot->id, $feedback->scheduleSlot->id);
    }

    public function test_feedback_has_fillable_attributes(): void
    {
        $fillable = [
            'from_user_id', 'to_user_id', 'conversation_id', 'schedule_slot_id',
            'rating', 'comment',
        ];

        $feedback = new Feedback();
        $this->assertEquals($fillable, $feedback->getFillable());
    }

    public function test_feedback_validates_rating_range(): void
    {
        $feedback = Feedback::factory()->create(['rating' => 5]);
        $this->assertGreaterThanOrEqual(1, $feedback->rating);
        $this->assertLessThanOrEqual(5, $feedback->rating);
    }
}
