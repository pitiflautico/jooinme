<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\ExternalLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_external_link_can_be_created(): void
    {
        $conversation = Conversation::factory()->create();

        $link = ExternalLink::factory()->create([
            'linkable_type' => Conversation::class,
            'linkable_id' => $conversation->id,
            'url' => 'https://zoom.us/j/123456',
        ]);

        $this->assertDatabaseHas('external_links', [
            'linkable_id' => $conversation->id,
            'url' => 'https://zoom.us/j/123456',
        ]);
    }

    public function test_external_link_has_polymorphic_linkable_relationship(): void
    {
        $conversation = Conversation::factory()->create();
        $link = ExternalLink::factory()->create([
            'linkable_type' => Conversation::class,
            'linkable_id' => $conversation->id,
        ]);

        $this->assertInstanceOf(Conversation::class, $link->linkable);
        $this->assertEquals($conversation->id, $link->linkable->id);
    }

    public function test_external_link_has_fillable_attributes(): void
    {
        $fillable = [
            'linkable_type', 'linkable_id', 'type', 'url', 'title', 'description',
        ];

        $link = new ExternalLink();
        $this->assertEquals($fillable, $link->getFillable());
    }

    public function test_external_link_default_type_is_other(): void
    {
        $link = ExternalLink::factory()->create();

        $this->assertContains($link->type, [
            'zoom', 'google_meet', 'teams', 'whatsapp', 'telegram', 'discord', 'other'
        ]);
    }
}
