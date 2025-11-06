<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    public function test_topic_can_be_created(): void
    {
        $topic = Topic::factory()->create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        $this->assertDatabaseHas('topics', [
            'name' => 'Technology',
            'slug' => 'technology',
        ]);
    }

    public function test_topic_has_conversations_relationship(): void
    {
        $topic = Topic::factory()->create();
        $conversation = Conversation::factory()->create(['topic_id' => $topic->id]);

        $this->assertTrue($topic->conversations->contains($conversation));
        $this->assertInstanceOf(Conversation::class, $topic->conversations->first());
    }

    public function test_topic_has_fillable_attributes(): void
    {
        $fillable = [
            'name', 'slug', 'category', 'description', 'icon', 'color', 'is_active',
        ];

        $topic = new Topic();
        $this->assertEquals($fillable, $topic->getFillable());
    }

    public function test_topic_casts_attributes_correctly(): void
    {
        $topic = new Topic();
        $casts = $topic->getCasts();

        $this->assertEquals('boolean', $casts['is_active']);
    }

    public function test_topic_slug_is_unique(): void
    {
        Topic::factory()->create(['slug' => 'unique-slug']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Topic::factory()->create(['slug' => 'unique-slug']);
    }

    public function test_topic_can_have_multiple_conversations(): void
    {
        $topic = Topic::factory()->create();

        Conversation::factory()->count(3)->create(['topic_id' => $topic->id]);

        $this->assertCount(3, $topic->conversations);
    }
}
