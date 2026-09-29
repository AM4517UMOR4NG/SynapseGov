<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemFixesVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_queue_number_generates_monotonically(): void
    {
        $q1 = Report::nextQueueNo();
        $today = now()->format('Ymd');
        $this->assertEquals("Q-{$today}-0001", $q1);

        // Create a report with that queue number
        $user = User::factory()->create(['role' => 'citizen']);
        $dept = Department::create(['name' => 'Dinas A', 'code' => 'DA', 'is_active' => true]);

        Report::create([
            'ticket_no' => 'RPT-TEST-001',
            'queue_no' => $q1,
            'title' => 'Test Report 1',
            'description' => 'Test Desc',
            'category' => 'Infrastruktur',
            'status' => 'verified',
            'priority' => 'medium',
            'user_id' => $user->id,
            'department_id' => $dept->id,
        ]);

        $q2 = Report::nextQueueNo();
        $this->assertEquals("Q-{$today}-0002", $q2);
    }

    public function test_complaint_ticket_number_has_date_prefix(): void
    {
        $user = User::factory()->create(['role' => 'citizen']);
        $dept = Department::create(['name' => 'Dinas B', 'code' => 'DB', 'is_active' => true]);

        $complaint = Complaint::create([
            'title' => 'Keluhan Jalan Rusak',
            'description' => 'Jalan berlubang besar',
            'category' => 'Infrastruktur',
            'status' => 'submitted',
            'priority' => 'high',
            'user_id' => $user->id,
            'department_id' => $dept->id,
        ]);

        $today = now()->format('Ymd');
        $this->assertStringStartsWith("CMP-{$today}-", $complaint->ticket_no);
    }

    public function test_user_assignments_relationship_works(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $this->assertCount(0, $staff->assignments);
    }
}
