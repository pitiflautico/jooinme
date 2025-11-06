<?php

namespace Tests\Unit\Models;

use App\Models\Attendance;
use App\Models\ScheduleSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_can_be_created(): void
    {
        $user = User::factory()->create();
        $slot = ScheduleSlot::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'schedule_slot_id' => $slot->id,
        ]);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'schedule_slot_id' => $slot->id,
        ]);
    }

    public function test_attendance_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $attendance->user);
        $this->assertEquals($user->id, $attendance->user->id);
    }

    public function test_attendance_belongs_to_schedule_slot(): void
    {
        $slot = ScheduleSlot::factory()->create();
        $attendance = Attendance::factory()->create(['schedule_slot_id' => $slot->id]);

        $this->assertInstanceOf(ScheduleSlot::class, $attendance->scheduleSlot);
        $this->assertEquals($slot->id, $attendance->scheduleSlot->id);
    }

    public function test_attendance_casts_attributes_correctly(): void
    {
        $attendance = new Attendance();
        $casts = $attendance->getCasts();

        $this->assertEquals('datetime', $casts['confirmed_at']);
        $this->assertEquals('datetime', $casts['checked_in_at']);
        $this->assertEquals('datetime', $casts['checked_out_at']);
        $this->assertEquals('boolean', $casts['attended']);
    }

    public function test_attendance_default_attended_is_false(): void
    {
        $attendance = Attendance::factory()->create();

        $this->assertFalse($attendance->attended);
    }

    public function test_attendance_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id', 'schedule_slot_id', 'confirmed_at', 'checked_in_at',
            'checked_out_at', 'attended', 'notes',
        ];

        $attendance = new Attendance();
        $this->assertEquals($fillable, $attendance->getFillable());
    }

    public function test_attendance_unique_user_slot_combination(): void
    {
        $user = User::factory()->create();
        $slot = ScheduleSlot::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'schedule_slot_id' => $slot->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'schedule_slot_id' => $slot->id,
        ]);
    }
}
