<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipationTest extends TestCase
{
    use RefreshDatabase;

    public function test_participation_can_be_created(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $participation = Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);

        $this->assertDatabaseHas('participations', [
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_participation_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $participation = Participation::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $participation->user);
        $this->assertEquals($user->id, $participation->user->id);
    }

    public function test_participation_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $participation = Participation::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertInstanceOf(Conversation::class, $participation->conversation);
        $this->assertEquals($conversation->id, $participation->conversation->id);
    }

    public function test_participation_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'conversation_id', 'status', 'role',
            'join_message', 'joined_at', 'left_at',
        ];

        $participation = new Participation();
        $this->assertEquals($fillable, $participation->getFillable());
    }

    public function test_participation_casts_attributes_correctly(): void
    {
        $participation = new Participation();
        $casts = $participation->getCasts();

        $this->assertEquals('datetime', $casts['joined_at']);
        $this->assertEquals('datetime', $casts['left_at']);
    }

    public function test_participation_unique_user_conversation_combination(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Participation::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_participation_default_status_is_pending(): void
    {
        $participation = Participation::factory()->create();

        $this->assertEquals('pending', $participation->status);
    }

    public function test_participation_default_role_is_participant(): void
    {
        $participation = Participation::factory()->create();

        $this->assertEquals('participant', $participation->role);
    }
}
