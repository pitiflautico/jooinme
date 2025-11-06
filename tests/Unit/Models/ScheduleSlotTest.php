<?php

namespace Tests\Unit\Models;

use App\Models\Attendance;
use App\Models\Conversation;
use App\Models\Feedback;
use App\Models\ScheduleSlot;
use App\Models\Transcription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleSlotTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_slot_can_be_created(): void
    {
        $conversation = Conversation::factory()->create();

        $slot = ScheduleSlot::factory()->create([
            'conversation_id' => $conversation->id,
            'scheduled_at' => now()->addDay(),
        ]);

        $this->assertDatabaseHas('schedule_slots', [
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_schedule_slot_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $slot = ScheduleSlot::factory()->create(['conversation_id' => $conversation->id]);

        $this->assertInstanceOf(Conversation::class, $slot->conversation);
        $this->assertEquals($conversation->id, $slot->conversation->id);
    }

    public function test_schedule_slot_has_attendances(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $attendance = Attendance::factory()->create(['schedule_slot_id' => $slot->id]);

        $this->assertTrue($slot->attendances->contains($attendance));
    }

    public function test_schedule_slot_has_feedback(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $feedback = Feedback::factory()->create(['schedule_slot_id' => $slot->id]);

        $this->assertTrue($slot->feedback->contains($feedback));
    }

    public function test_schedule_slot_has_one_transcription(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $transcription = Transcription::factory()->create(['schedule_slot_id' => $slot->id]);

        $this->assertInstanceOf(Transcription::class, $slot->transcription);
        $this->assertEquals($transcription->id, $slot->transcription->id);
    }

    public function test_schedule_slot_casts_attributes_correctly(): void
    {
        $slot = new ScheduleSlot();
        $casts = $slot->getCasts();

        $this->assertEquals('datetime', $casts['scheduled_at']);
        $this->assertEquals('datetime', $casts['ends_at']);
        $this->assertEquals('array', $casts['metadata']);
    }

    public function test_schedule_slot_default_status_is_scheduled(): void
    {
        $slot = ScheduleSlot::factory()->create();

        $this->assertEquals('scheduled', $slot->status);
    }

    public function test_schedule_slot_has_fillable_attributes(): void
    {
        $fillable = [
            'conversation_id', 'scheduled_at', 'ends_at', 'status',
            'confirmed_participants', 'attended_participants', 'meeting_url',
            'recording_url', 'notes', 'metadata',
        ];

        $slot = new ScheduleSlot();
        $this->assertEquals($fillable, $slot->getFillable());
    }
}
