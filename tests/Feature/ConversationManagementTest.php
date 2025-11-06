<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_conversation(): void
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();

        $response = $this->actingAs($user)->post('/conversations', [
            'topic_id' => $topic->id,
            'title' => 'New Tech Discussion',
            'description' => 'Weekly discussion about technology trends',
            'frequency' => 'weekly',
            'type' => 'online',
            'privacy' => 'public',
            'max_participants' => 10,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('conversations', [
            'title' => 'New Tech Discussion',
            'owner_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_conversation(): void
    {
        $topic = Topic::factory()->create();

        $response = $this->post('/conversations', [
            'topic_id' => $topic->id,
            'title' => 'New Tech Discussion',
            'description' => 'Weekly discussion about technology trends',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_public_conversation(): void
    {
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'is_active' => true,
        ]);

        $response = $this->get('/conversations/' . $conversation->slug);

        $response->assertStatus(200);
        $response->assertSee($conversation->title);
    }

    public function test_owner_can_update_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put('/conversations/' . $conversation->id, [
            'title' => 'Updated Title',
            'description' => $conversation->description,
            'frequency' => $conversation->frequency,
            'type' => $conversation->type,
            'privacy' => $conversation->privacy,
            'max_participants' => $conversation->max_participants,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_non_owner_cannot_update_conversation(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->put('/conversations/' . $conversation->id, [
            'title' => 'Hacked Title',
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_conversation(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete('/conversations/' . $conversation->id);

        $response->assertStatus(302);
        $this->assertSoftDeleted('conversations', [
            'id' => $conversation->id,
        ]);
    }

    public function test_conversation_list_shows_public_conversations(): void
    {
        Conversation::factory()->count(5)->create([
            'privacy' => 'public',
            'is_active' => true,
        ]);

        Conversation::factory()->create([
            'privacy' => 'private',
            'is_active' => true,
        ]);

        $response = $this->get('/conversations');

        $response->assertStatus(200);
        $response->assertViewHas('conversations', function ($conversations) {
            return $conversations->count() === 5;
        });
    }

    public function test_user_can_search_conversations_by_title(): void
    {
        Conversation::factory()->create([
            'title' => 'Laravel Development',
            'privacy' => 'public',
        ]);

        Conversation::factory()->create([
            'title' => 'React Development',
            'privacy' => 'public',
        ]);

        $response = $this->get('/conversations?search=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravel Development');
        $response->assertDontSee('React Development');
    }

    public function test_conversation_shows_participant_count(): void
    {
        $conversation = Conversation::factory()->create([
            'privacy' => 'public',
            'current_participants' => 5,
            'max_participants' => 10,
        ]);

        $response = $this->get('/conversations/' . $conversation->slug);

        $response->assertStatus(200);
        $response->assertSee('5');
        $response->assertSee('10');
    }
}
