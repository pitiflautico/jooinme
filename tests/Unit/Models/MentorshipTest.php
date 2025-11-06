<?php

namespace Tests\Unit\Models;

use App\Models\Mentorship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentorshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentorship_can_be_created(): void
    {
        $mentor = User::factory()->create();
        $mentee = User::factory()->create();

        $mentorship = Mentorship::factory()->create([
            'mentor_id' => $mentor->id,
            'mentee_id' => $mentee->id,
        ]);

        $this->assertDatabaseHas('mentorships', [
            'mentor_id' => $mentor->id,
            'mentee_id' => $mentee->id,
        ]);
    }

    public function test_mentorship_belongs_to_mentor(): void
    {
        $mentor = User::factory()->create();
        $mentorship = Mentorship::factory()->create(['mentor_id' => $mentor->id]);

        $this->assertInstanceOf(User::class, $mentorship->mentor);
        $this->assertEquals($mentor->id, $mentorship->mentor->id);
    }

    public function test_mentorship_belongs_to_mentee(): void
    {
        $mentee = User::factory()->create();
        $mentorship = Mentorship::factory()->create(['mentee_id' => $mentee->id]);

        $this->assertInstanceOf(User::class, $mentorship->mentee);
        $this->assertEquals($mentee->id, $mentorship->mentee->id);
    }

    public function test_mentorship_accept_method_updates_status(): void
    {
        $mentorship = Mentorship::factory()->create(['status' => 'pending']);

        $mentorship->accept();

        $this->assertEquals('active', $mentorship->status);
        $this->assertNotNull($mentorship->started_at);
    }

    public function test_mentorship_complete_method_updates_status(): void
    {
        $mentorship = Mentorship::factory()->create(['status' => 'active']);

        $mentorship->complete();

        $this->assertEquals('completed', $mentorship->status);
        $this->assertNotNull($mentorship->completed_at);
    }

    public function test_mentorship_casts_attributes_correctly(): void
    {
        $mentorship = new Mentorship();
        $casts = $mentorship->getCasts();

        $this->assertEquals('decimal:2', $casts['price']);
        $this->assertEquals('datetime', $casts['scheduled_at']);
        $this->assertEquals('datetime', $casts['started_at']);
        $this->assertEquals('datetime', $casts['completed_at']);
    }

    public function test_mentorship_default_status_is_pending(): void
    {
        $mentorship = Mentorship::factory()->create();

        $this->assertEquals('pending', $mentorship->status);
    }

    public function test_mentorship_default_currency_is_usd(): void
    {
        $mentorship = Mentorship::factory()->create();

        $this->assertEquals('USD', $mentorship->currency);
    }

    public function test_mentorship_has_fillable_attributes(): void
    {
        $fillable = [
            'mentor_id', 'mentee_id', 'topic', 'description', 'status',
            'price', 'currency', 'duration_minutes', 'scheduled_at',
            'started_at', 'completed_at', 'notes',
        ];

        $mentorship = new Mentorship();
        $this->assertEquals($fillable, $mentorship->getFillable());
    }
}
