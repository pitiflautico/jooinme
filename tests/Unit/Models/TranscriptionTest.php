<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\ScheduleSlot;
use App\Models\Transcription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transcription_can_be_created(): void
    {
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create();

        $transcription = Transcription::factory()->create([
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
        ]);

        $this->assertDatabaseHas('transcriptions', [
            'conversation_id' => $conversation->id,
            'schedule_slot_id' => $slot->id,
        ]);
    }

    public function test_transcription_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $transcription = Transcription::factory()->create([
            'conversation_id' => $conversation->id,
        ]);

        $this->assertInstanceOf(Conversation::class, $transcription->conversation);
        $this->assertEquals($conversation->id, $transcription->conversation->id);
    }

    public function test_transcription_belongs_to_schedule_slot(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $transcription = Transcription::factory()->create([
            'schedule_slot_id' => $slot->id,
        ]);

        $this->assertInstanceOf(ScheduleSlot::class, $transcription->scheduleSlot);
        $this->assertEquals($slot->id, $transcription->scheduleSlot->id);
    }

    public function test_transcription_casts_attributes_correctly(): void
    {
        $transcription = new Transcription();
        $casts = $transcription->getCasts();

        $this->assertEquals('array', $casts['key_points']);
    }

    public function test_transcription_default_status_is_processing(): void
    {
        $transcription = Transcription::factory()->create();

        $this->assertEquals('processing', $transcription->status);
    }

    public function test_transcription_has_fillable_attributes(): void
    {
        $fillable = [
            'conversation_id', 'schedule_slot_id', 'content', 'summary',
            'key_points', 'status', 'processed_at',
        ];

        $transcription = new Transcription();
        $this->assertEquals($fillable, $transcription->getFillable());
    }
}
