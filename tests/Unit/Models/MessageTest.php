<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_can_be_created(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $message = Message::factory()->create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'content' => 'Test message',
        ]);

        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'content' => 'Test message',
        ]);
    }

    public function test_message_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $message->user);
        $this->assertEquals($user->id, $message->user->id);
    }

    public function test_message_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $message = Message::factory()->create(['conversation_id' => $conversation->id]);

        $this->assertInstanceOf(Conversation::class, $message->conversation);
        $this->assertEquals($conversation->id, $message->conversation->id);
    }

    public function test_message_default_type_is_text(): void
    {
        $message = Message::factory()->create();

        $this->assertEquals('text', $message->type);
    }

    public function test_message_default_is_edited_is_false(): void
    {
        $message = Message::factory()->create();

        $this->assertFalse($message->is_edited);
    }

    public function test_message_casts_attributes_correctly(): void
    {
        $message = new Message();
        $casts = $message->getCasts();

        $this->assertEquals('boolean', $casts['is_edited']);
        $this->assertEquals('datetime', $casts['edited_at']);
    }

    public function test_message_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'conversation_id', 'content', 'type', 'is_edited', 'edited_at',
        ];

        $message = new Message();
        $this->assertEquals($fillable, $message->getFillable());
    }

    public function test_message_can_be_soft_deleted(): void
    {
        $message = Message::factory()->create();
        $messageId = $message->id;

        $message->delete();

        $this->assertSoftDeleted('messages', ['id' => $messageId]);
    }
}
