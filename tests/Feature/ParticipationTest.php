<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_join_public_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'max_participants' => 10,
            'current_participants' => 5,
        ]);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/join");

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);
    }

    public function test_user_cannot_join_full_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'max_participants' => 10,
            'current_participants' => 10,
        ]);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/join");

        $response->assertStatus(403);
        $this->assertDatabaseMissing('participations', [
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_joining_moderated_conversation_requires_approval(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'privacy' => 'moderated',
            'require_approval' => true,
        ]);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/join", [
            'join_message' => 'I would like to join this conversation',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_join_same_conversation_twice(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create(['privacy' => 'public']);

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/join");

        $response->assertStatus(409); // Conflict
    }

    public function test_user_can_leave_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/leave");

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'id' => $participation->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_owner_can_approve_pending_participation(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $owner->id,
            'privacy' => 'moderated',
        ]);

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->post("/participations/{$participation->id}/approve");

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'id' => $participation->id,
            'status' => 'accepted',
        ]);
    }

    public function test_owner_can_reject_pending_participation(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $owner->id,
            'privacy' => 'moderated',
        ]);

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->post("/participations/{$participation->id}/reject");

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'id' => $participation->id,
            'status' => 'rejected',
        ]);
    }

    public function test_non_owner_cannot_approve_participation(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($otherUser)->post("/participations/{$participation->id}/approve");

        $response->assertStatus(403);
    }

    public function test_owner_can_remove_participant(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($owner)->delete("/participations/{$participation->id}");

        $response->assertStatus(302);
        $this->assertDatabaseHas('participations', [
            'id' => $participation->id,
            'status' => 'cancelled',
        ]);
    }
}
