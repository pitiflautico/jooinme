<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\Participation;
use App\Models\ScheduleSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_leave_feedback_for_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create([
            'conversation_id' => $conversation->id,
            'status' => 'completed',
        ]);

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($user)->post('/feedback', [
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
            'to_user_id' => $conversation->owner_id,
            'rating' => 5,
            'comment' => 'Great conversation!',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('feedback', [
            'from_user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'rating' => 5,
        ]);
    }

    public function test_non_participant_cannot_leave_feedback(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $response = $this->actingAs($user)->post('/feedback', [
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
            'to_user_id' => $conversation->owner_id,
            'rating' => 5,
            'comment' => 'Great conversation!',
        ]);

        $response->assertStatus(403);
    }

    public function test_feedback_rating_must_be_between_1_and_5(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($user)->post('/feedback', [
            'conversation_id' => $conversation->id,
            'to_user_id' => $conversation->owner_id,
            'rating' => 6,
            'comment' => 'Invalid rating',
        ]);

        $response->assertStatus(422);
        $response->assertSessionHasErrors('rating');
    }

    public function test_user_can_view_conversation_feedback(): void
    {
        $conversation = Conversation::factory()->create();

        Feedback::factory()->count(5)->create([
            'conversation_id' => $conversation->id,
        ]);

        $response = $this->get("/conversations/{$conversation->id}/feedback");

        $response->assertStatus(200);
        $response->assertViewHas('feedback', function ($feedback) {
            return $feedback->count() === 5;
        });
    }

    public function test_user_can_view_received_feedback(): void
    {
        $user = User::factory()->create();

        Feedback::factory()->count(3)->create([
            'to_user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/profile/feedback');

        $response->assertStatus(200);
        $response->assertViewHas('feedback', function ($feedback) {
            return $feedback->count() === 3;
        });
    }

    public function test_conversation_average_rating_is_calculated_correctly(): void
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

        $response = $this->get("/conversations/{$conversation->slug}");

        $response->assertStatus(200);
        $response->assertSee('4.0'); // Average rating
    }

    public function test_user_cannot_leave_duplicate_feedback_for_same_slot(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        Feedback::factory()->create([
            'from_user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
        ]);

        $response = $this->actingAs($user)->post('/feedback', [
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
            'to_user_id' => $conversation->owner_id,
            'rating' => 4,
            'comment' => 'Another feedback',
        ]);

        $response->assertStatus(409); // Conflict
    }
}
