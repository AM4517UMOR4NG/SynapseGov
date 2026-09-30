<?php

namespace Tests\Feature;

use App\Exceptions\InvalidStatusTransition;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_allowed_status_transition_is_saved(): void
    {
        $report = Report::factory()->create(['ticket_no' => 'RPT-20261001-TEST01']);

        $report->update(['status' => 'verified']);

        $this->assertSame('verified', $report->fresh()->status);
    }

    public function test_status_transition_outside_the_workflow_is_rejected(): void
    {
        $report = Report::factory()->create(['ticket_no' => 'RPT-20261001-TEST02', 'status' => 'closed']);

        $this->expectException(InvalidStatusTransition::class);

        $report->update(['status' => 'verified']);
    }

    public function test_public_tracking_shows_status_but_not_report_content(): void
    {
        Report::factory()->create([
            'ticket_no' => 'RPT-20261001-TEST03',
            'title' => 'Judul rahasia pelapor',
        ]);

        $this->get('/lacak?tiket=rpt-20261001-test03')
            ->assertOk()
            ->assertSee('RPT-20261001-TEST03')
            ->assertSee('Baru masuk')
            ->assertDontSee('Judul rahasia pelapor');
    }

    public function test_public_tracking_rejects_malformed_ticket_numbers(): void
    {
        $this->get('/lacak?tiket=abc')
            ->assertOk()
            ->assertSee('Format nomor tiket tidak valid');
    }
}
