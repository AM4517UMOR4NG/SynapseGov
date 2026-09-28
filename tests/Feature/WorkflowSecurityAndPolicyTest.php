<?php

namespace Tests\Feature;

use App\Events\CommentAdded;
use App\Models\Comment;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Report;
use App\Models\User;
use App\Notifications\CommentAddedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WorkflowSecurityAndPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_department_validation_on_report_submission(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'is_active' => true,
        ]);

        $activeDept = Department::create([
            'name' => 'Dinas Kesehatan',
            'code' => 'DINKES',
            'is_active' => true,
        ]);

        $inactiveDept = Department::create([
            'name' => 'Dinas Arsip Nonaktif',
            'code' => 'ARSIP_OLD',
            'is_active' => false,
        ]);

        // Attempting to submit report to inactive department must fail validation
        $response = $this->actingAs($citizen)->post(route('citizen.reports.store'), [
            'title' => 'Laporan Kesehatan',
            'description' => 'Deskripsi laporan kesehatan warga',
            'category' => 'Kesehatan',
            'department_id' => $inactiveDept->id,
            'priority' => 'medium',
        ]);

        $response->assertSessionHasErrors('department_id');

        // Submitting to active department must succeed
        $successResponse = $this->actingAs($citizen)->post(route('citizen.reports.store'), [
            'title' => 'Laporan Kesehatan Valid',
            'description' => 'Deskripsi laporan kesehatan warga valid',
            'category' => 'Kesehatan',
            'department_id' => $activeDept->id,
            'priority' => 'medium',
        ]);

        $successResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reports', [
            'title' => 'Laporan Kesehatan Valid',
            'department_id' => $activeDept->id,
        ]);
    }

    public function test_internal_comment_does_not_leak_notification_to_citizen(): void
    {
        Notification::fake();

        $citizen = User::factory()->create(['role' => 'citizen']);
        $admin = User::factory()->create(['role' => 'admin']);
        $dept = Department::create(['name' => 'Dinas PUPR', 'code' => 'PUPR', 'is_active' => true]);

        $report = Report::create([
            'ticket_no' => 'REP-TEST-001',
            'title' => 'Jalan Rusak',
            'description' => 'Jalan berlubang besar',
            'category' => 'Infrastruktur',
            'department_id' => $dept->id,
            'user_id' => $citizen->id,
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        // Create an internal comment by admin
        $internalComment = Comment::create([
            'commentable_type' => Report::class,
            'commentable_id' => $report->id,
            'user_id' => $admin->id,
            'content' => 'Catatan internal investigasi dugaan fraud tender',
            'is_internal' => true,
        ]);

        event(new CommentAdded($internalComment));

        // Citizen should NOT receive notification for internal comments
        Notification::assertNotSentTo($citizen, CommentAddedNotification::class);
    }

    public function test_staff_can_view_reports_in_their_department_via_policy(): void
    {
        $dept = Department::create(['name' => 'Dinas Pendidikan', 'code' => 'DISDIK', 'is_active' => true]);
        $citizen = User::factory()->create(['role' => 'citizen']);
        $staff = User::factory()->create(['role' => 'staff', 'department_id' => $dept->id]);

        $report = Report::create([
            'ticket_no' => 'REP-TEST-002',
            'title' => 'Gedung Sekolah Rusak',
            'description' => 'Atap sekolah bocor',
            'category' => 'Pendidikan',
            'department_id' => $dept->id,
            'user_id' => $citizen->id,
            'status' => 'submitted',
            'priority' => 'medium',
            'assigned_to' => null, // Unassigned
        ]);

        $this->assertTrue($staff->can('view', $report));
    }

    public function test_admin_complaints_assign_route_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dept = Department::create(['name' => 'Dinas Sosial', 'code' => 'DINSOS', 'is_active' => true]);
        $citizen = User::factory()->create(['role' => 'citizen']);
        $staff = User::factory()->create(['role' => 'staff', 'department_id' => $dept->id, 'is_active' => true]);

        $complaint = Complaint::create([
            'ticket_no' => 'CMP-TEST-001',
            'title' => 'Pelayanan Lambat',
            'description' => 'Petugas lambat melayani',
            'category' => 'Pelayanan',
            'department_id' => $dept->id,
            'user_id' => $citizen->id,
            'status' => 'submitted',
            'priority' => 'medium',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.complaints.assign', $complaint->id), [
            'assigned_to' => $staff->id,
            'notes' => 'Harap segera tindak lanjuti.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'assigned_to' => $staff->id,
            'status' => 'investigating',
        ]);
        $this->assertDatabaseHas('assignments', [
            'assignable_type' => Complaint::class,
            'assignable_id' => $complaint->id,
            'assigned_to' => $staff->id,
            'status' => 'active',
        ]);
    }

    public function test_confirm_to_admin_transitions_to_awaiting_admin_approval(): void
    {
        \Illuminate\Support\Facades\Event::fake([\App\Events\ReportStatusChanged::class]);

        $dept = Department::create(['name' => 'Dinas Perhubungan', 'code' => 'DISHUB', 'is_active' => true]);
        $staff = User::factory()->create(['role' => 'staff', 'department_id' => $dept->id, 'is_active' => true]);
        $citizen = User::factory()->create(['role' => 'citizen']);

        $report = Report::create([
            'ticket_no' => 'REP-DISHUB-001',
            'title' => 'Rambu Rusak',
            'description' => 'Rambu jalan roboh',
            'category' => 'Infrastruktur',
            'department_id' => $dept->id,
            'user_id' => $citizen->id,
            'assigned_to' => $staff->id,
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        $response = $this->actingAs($staff)->post(route('administration.reports.confirm_to_admin', $report->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'awaiting_admin_approval',
            'assigned_to' => null,
        ]);

        \Illuminate\Support\Facades\Event::assertDispatched(\App\Events\ReportStatusChanged::class, function ($event) use ($report) {
            return $event->report->id === $report->id
                && $event->newStatus === 'awaiting_admin_approval';
        });
    }

    public function test_department_isolation_prevents_unauthorized_actions(): void
    {
        $deptA = Department::create(['name' => 'Departemen A', 'code' => 'DEPT_A', 'is_active' => true]);
        $deptB = Department::create(['name' => 'Departemen B', 'code' => 'DEPT_B', 'is_active' => true]);

        $staffA = User::factory()->create(['role' => 'staff', 'department_id' => $deptA->id, 'is_active' => true]);
        $citizen = User::factory()->create(['role' => 'citizen']);

        // Report in Department B
        $reportB = Report::create([
            'ticket_no' => 'REP-DEPTB-001',
            'title' => 'Laporan Dept B',
            'description' => 'Khusus Dept B',
            'category' => 'Umum',
            'department_id' => $deptB->id,
            'user_id' => $citizen->id,
            'status' => 'submitted',
            'priority' => 'low',
        ]);

        // Staff A tries to confirm report in Dept B - must be 404 or 403
        $response = $this->actingAs($staffA)->post(route('administration.reports.confirm', $reportB->id));
        $this->assertTrue(in_array($response->getStatusCode(), [403, 404]));
    }
}
