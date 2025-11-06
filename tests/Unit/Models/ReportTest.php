<?php

namespace Tests\Unit\Models;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_can_be_created(): void
    {
        $reporter = User::factory()->create();

        $report = Report::factory()->create([
            'reporter_id' => $reporter->id,
            'reportable_type' => User::class,
            'reportable_id' => User::factory()->create()->id,
        ]);

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $reporter->id,
        ]);
    }

    public function test_report_belongs_to_reporter(): void
    {
        $reporter = User::factory()->create();
        $report = Report::factory()->create(['reporter_id' => $reporter->id]);

        $this->assertInstanceOf(User::class, $report->reporter);
        $this->assertEquals($reporter->id, $report->reporter->id);
    }

    public function test_report_has_polymorphic_reportable_relationship(): void
    {
        $reportedUser = User::factory()->create();
        $report = Report::factory()->create([
            'reportable_type' => User::class,
            'reportable_id' => $reportedUser->id,
        ]);

        $this->assertInstanceOf(User::class, $report->reportable);
        $this->assertEquals($reportedUser->id, $report->reportable->id);
    }

    public function test_report_default_status_is_pending(): void
    {
        $report = Report::factory()->create();

        $this->assertEquals('pending', $report->status);
    }

    public function test_report_has_fillable_attributes(): void
    {
        $fillable = [
            'reporter_id', 'reportable_type', 'reportable_id', 'reason',
            'description', 'status', 'reviewed_at', 'reviewed_by',
        ];

        $report = new Report();
        $this->assertEquals($fillable, $report->getFillable());
    }

    public function test_report_casts_attributes_correctly(): void
    {
        $report = new Report();
        $casts = $report->getCasts();

        $this->assertEquals('datetime', $casts['reviewed_at']);
    }
}
