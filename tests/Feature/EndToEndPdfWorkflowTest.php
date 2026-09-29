<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EndToEndPdfWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $citizen;
    private User $admin;
    private User $staff;
    private User $departmentHead;
    private User $unauthorizedCitizen;
    private Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->department = Department::create([
            'name' => 'Dinas Tata Ruang & Bangunan',
            'code' => 'DTRB',
            'is_active' => true,
        ]);

        $this->citizen = User::factory()->create([
            'name' => 'Warga Pelapor',
            'email' => 'citizen@test.com',
            'role' => 'citizen',
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Administrator Utama',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->staff = User::factory()->create([
            'name' => 'Staff Lapangan',
            'email' => 'staff@test.com',
            'role' => 'staff',
            'department_id' => $this->department->id,
            'is_active' => true,
        ]);

        $this->departmentHead = User::factory()->create([
            'name' => 'Kepala Dinas',
            'email' => 'head@test.com',
            'role' => 'department_head',
            'department_id' => $this->department->id,
            'is_active' => true,
        ]);

        $this->unauthorizedCitizen = User::factory()->create([
            'name' => 'Warga Lain',
            'email' => 'other@test.com',
            'role' => 'citizen',
            'is_active' => true,
        ]);
    }

    public function test_complete_pdf_lifecycle_from_citizen_to_admin_to_staff_to_head_and_closure(): void
    {
        // -------------------------------------------------------------
        // Step 1: Citizen submits a report with a PDF document
        // -------------------------------------------------------------
        $fakePdf = UploadedFile::fake()->create('dokumen_bukti.pdf', 250, 'application/pdf');

        $submitResponse = $this->actingAs($this->citizen)->post(route('citizen.reports.store'), [
            'title' => 'Laporan Kerusakan Fasilitas Umum',
            'description' => 'Mohon ditinjau dokumen teknis terlampir.',
            'category' => 'Infrastruktur',
            'department_id' => $this->department->id,
            'priority' => 'high',
            'attachments' => [$fakePdf],
        ]);

        $submitResponse->assertSessionHasNoErrors();

        /** @var Report $report */
        $report = Report::where('user_id', $this->citizen->id)->latest()->first();
        $this->assertNotNull($report);
        $this->assertNotEmpty($report->attachments);
        $pdfFilename = basename($report->attachments[0]);

        // Verify PDF file exists in fake storage
        $storedPath = $report->attachments[0];
        $fullStoragePath = str_starts_with($storedPath, 'public/') ? $storedPath : 'public/'.$storedPath;
        Storage::assertExists($fullStoragePath);

        // -------------------------------------------------------------
        // Step 2: Citizen & Admin PDF Preview and Download verification
        // -------------------------------------------------------------
        // Citizen reads PDF
        $citizenPreview = $this->actingAs($this->citizen)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $citizenPreview->assertStatus(200);
        $citizenPreview->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('inline', $citizenPreview->headers->get('Content-Disposition') ?? '');

        // Citizen downloads PDF
        $citizenDownload = $this->actingAs($this->citizen)
            ->get(route('files.download', ['report', $report->id, $pdfFilename]));
        $citizenDownload->assertStatus(200);

        // Admin reads PDF
        $adminPreview = $this->actingAs($this->admin)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $adminPreview->assertStatus(200);
        $adminPreview->assertHeader('Content-Type', 'application/pdf');

        // Admin downloads PDF
        $adminDownload = $this->actingAs($this->admin)
            ->get(route('files.download', ['report', $report->id, $pdfFilename]));
        $adminDownload->assertStatus(200);

        // -------------------------------------------------------------
        // Step 3: Admin assigns report to Staff
        // -------------------------------------------------------------
        $assignResponse = $this->actingAs($this->admin)
            ->post(route('workflow.reports.admin_assign_staff', $report->id), [
                'assigned_to' => $this->staff->id,
                'notes' => 'Tolong staff verifikasi dokumen teknis ini.',
            ]);

        $assignResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals($this->staff->id, $report->assigned_to);
        $this->assertEquals('assigned', $report->status);

        // Staff can now read PDF inline
        $staffPreview = $this->actingAs($this->staff)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $staffPreview->assertStatus(200);
        $staffPreview->assertHeader('Content-Type', 'application/pdf');

        // Staff downloads PDF
        $staffDownload = $this->actingAs($this->staff)
            ->get(route('files.download', ['report', $report->id, $pdfFilename]));
        $staffDownload->assertStatus(200);

        // Staff can view file details list
        $staffViewFiles = $this->actingAs($this->staff)
            ->get(route('files.view', ['report', $report->id]));
        $staffViewFiles->assertStatus(200);

        // -------------------------------------------------------------
        // Step 4: Staff confirms and forwards report to Department Head
        // -------------------------------------------------------------
        $forwardResponse = $this->actingAs($this->staff)
            ->post(route('workflow.reports.staff_confirm_forward', $report->id));

        $forwardResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals($this->departmentHead->id, $report->assigned_to);

        // Department Head can now read PDF inline
        $headPreview = $this->actingAs($this->departmentHead)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $headPreview->assertStatus(200);
        $headPreview->assertHeader('Content-Type', 'application/pdf');

        // Department Head downloads PDF
        $headDownload = $this->actingAs($this->departmentHead)
            ->get(route('files.download', ['report', $report->id, $pdfFilename]));
        $headDownload->assertStatus(200);

        // Department Head views file list
        $headViewFiles = $this->actingAs($this->departmentHead)
            ->get(route('files.view', ['report', $report->id]));
        $headViewFiles->assertStatus(200);

        // -------------------------------------------------------------
        // Step 5: Department Head reviews and returns to Staff
        // -------------------------------------------------------------
        $returnResponse = $this->actingAs($this->departmentHead)
            ->post(route('workflow.reports.head_review_return', $report->id), [
                'assigned_to' => $this->staff->id,
                'notes' => 'Dokumen PDF sudah saya verifikasi, silakan eksekusi perbaikan.',
            ]);

        $returnResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('reviewed', $report->status);
        $this->assertEquals($this->staff->id, $report->assigned_to);

        // Staff still reads PDF successfully
        $staffPreviewAfterReturn = $this->actingAs($this->staff)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $staffPreviewAfterReturn->assertStatus(200);

        // -------------------------------------------------------------
        // Step 6: Staff completes work and confirms to Admin
        // -------------------------------------------------------------
        $completeResponse = $this->actingAs($this->staff)
            ->post(route('workflow.reports.staff_confirm_admin', $report->id), [
                'completion_notes' => 'Pekerjaan perbaikan selesai dilakukan sesuai dokumen PDF teknis.',
            ]);

        $completeResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('awaiting_admin_approval', $report->status);

        // -------------------------------------------------------------
        // Step 7: Admin approves and closes report
        // -------------------------------------------------------------
        $approveResponse = $this->actingAs($this->admin)
            ->post(route('workflow.reports.admin_approve_close', $report->id), [
                'final_notes' => 'Hasil verifikasi disetujui, laporan resmi ditutup.',
            ]);

        $approveResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('resolved', $report->status);

        // -------------------------------------------------------------
        // Step 8: Full report document download (PDF and CSV endpoints)
        // -------------------------------------------------------------
        $adminDocDownload = $this->actingAs($this->admin)
            ->get(route('reports.download_pdf', $report->id));
        $adminDocDownload->assertStatus(200);

        $citizenDocDownload = $this->actingAs($this->citizen)
            ->get(route('reports.download_pdf', $report->id));
        $citizenDocDownload->assertStatus(200);

        // -------------------------------------------------------------
        // Step 9: Unauthorized user is strictly blocked (Security check)
        // -------------------------------------------------------------
        $unauthorizedPreview = $this->actingAs($this->unauthorizedCitizen)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $this->assertEquals(403, $unauthorizedPreview->getStatusCode());

        // Web request gets redirected back with flash error
        $unauthorizedDownload = $this->actingAs($this->unauthorizedCitizen)
            ->get(route('files.download', ['report', $report->id, $pdfFilename]));
        $unauthorizedDownload->assertRedirect();
        $unauthorizedDownload->assertSessionHas('error');

        // JSON/API request gets 403 Forbidden
        $unauthorizedDownloadJson = $this->actingAs($this->unauthorizedCitizen)
            ->getJson(route('files.download', ['report', $report->id, $pdfFilename]));
        $unauthorizedDownloadJson->assertStatus(403);
    }

    public function test_admin_direct_assign_to_head_reject_and_revision_cycle(): void
    {
        $fakePdf = UploadedFile::fake()->create('dokumen_revisi.pdf', 300, 'application/pdf');

        $this->actingAs($this->citizen)->post(route('citizen.reports.store'), [
            'title' => 'Laporan Butuh Pengawasan Kepala Dinas',
            'description' => 'Dokumen terlampir untuk kepala dinas.',
            'category' => 'Infrastruktur',
            'department_id' => $this->department->id,
            'priority' => 'urgent',
            'attachments' => [$fakePdf],
        ]);

        /** @var Report $report */
        $report = Report::where('user_id', $this->citizen->id)->latest()->first();
        $pdfFilename = basename($report->attachments[0]);

        // 1. Admin assigns directly to Department Head
        $assignHeadResponse = $this->actingAs($this->admin)
            ->post(route('workflow.reports.admin_assign_head', $report->id));
        $assignHeadResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals($this->departmentHead->id, $report->assigned_to);

        // 2. Head reviews and returns to Staff
        $headReviewResponse = $this->actingAs($this->departmentHead)
            ->post(route('workflow.reports.head_review_return', $report->id), [
                'assigned_to' => $this->staff->id,
                'notes' => 'Tolong staff periksa detail PDF dokumen teknis.',
            ]);
        $headReviewResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('reviewed', $report->status);
        $this->assertEquals($this->staff->id, $report->assigned_to);

        // 3. Staff confirms completion to Admin
        $staffCompleteResponse = $this->actingAs($this->staff)
            ->post(route('workflow.reports.staff_confirm_admin', $report->id), [
                'completion_notes' => 'Sudah diperiksa dan dilaksanakan.',
            ]);
        $staffCompleteResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('awaiting_admin_approval', $report->status);

        // 4. Admin rejects back to Staff for revision
        $adminRejectResponse = $this->actingAs($this->admin)
            ->post(route('workflow.reports.admin_reject_staff', $report->id), [
                'assigned_to' => $this->staff->id,
                'rejection_reason' => 'Masih ada data yang kurang pada PDF pendukung.',
            ]);
        $adminRejectResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals('needs_revision', $report->status);
        $this->assertEquals($this->staff->id, $report->assigned_to);

        // 5. Staff re-confirms and forwards to Head
        $staffReForwardResponse = $this->actingAs($this->staff)
            ->post(route('workflow.reports.staff_confirm_forward', $report->id));
        $staffReForwardResponse->assertSessionHasNoErrors();
        $report->refresh();
        $this->assertEquals($this->departmentHead->id, $report->assigned_to);

        // 6. Verify PDF is still readable by Head
        $headPreview = $this->actingAs($this->departmentHead)
            ->get(route('files.preview_image', ['report', $report->id, $pdfFilename]));
        $headPreview->assertStatus(200);
    }

    public function test_complaint_pdf_submission_and_role_access(): void
    {
        $fakePdf = UploadedFile::fake()->create('keluhan_bukti.pdf', 150, 'application/pdf');

        $submitComplaintResponse = $this->actingAs($this->citizen)
            ->post(route('citizen.complaints.store'), [
                'title' => 'Keluhan Pelayanan Kurang Memuaskan',
                'description' => 'Bukti surat terlampir dalam bentuk PDF.',
                'category' => 'Pelayanan Publik',
                'department_id' => $this->department->id,
                'priority' => 'medium',
                'attachments' => [$fakePdf],
            ]);

        $submitComplaintResponse->assertSessionHasNoErrors();

        /** @var \App\Models\Complaint $complaint */
        $complaint = \App\Models\Complaint::where('user_id', $this->citizen->id)->latest()->first();
        $this->assertNotNull($complaint);
        $this->assertNotEmpty($complaint->attachments);
        $pdfFilename = basename($complaint->attachments[0]);

        // Citizen previews complaint PDF
        $citizenPreview = $this->actingAs($this->citizen)
            ->get(route('files.preview_image', ['complaint', $complaint->id, $pdfFilename]));
        $citizenPreview->assertStatus(200);
        $citizenPreview->assertHeader('Content-Type', 'application/pdf');

        // Admin assigns complaint to Staff
        $assignResponse = $this->actingAs($this->admin)
            ->post(route('admin.complaints.assign', $complaint->id), [
                'assigned_to' => $this->staff->id,
                'notes' => 'Tolong investigasi keluhan ini.',
            ]);
        $assignResponse->assertSessionHasNoErrors();
        $complaint->refresh();
        $this->assertEquals($this->staff->id, $complaint->assigned_to);

        // Staff previews complaint PDF
        $staffPreview = $this->actingAs($this->staff)
            ->get(route('files.preview_image', ['complaint', $complaint->id, $pdfFilename]));
        $staffPreview->assertStatus(200);

        // Unauthorized user cannot preview complaint PDF
        $unauthPreview = $this->actingAs($this->unauthorizedCitizen)
            ->get(route('files.preview_image', ['complaint', $complaint->id, $pdfFilename]));
        $this->assertEquals(403, $unauthPreview->getStatusCode());
    }

    public function test_csv_and_administration_report_zip_download(): void
    {
        $fakePdf = UploadedFile::fake()->create('laporan_berkas.pdf', 100, 'application/pdf');

        $this->actingAs($this->citizen)->post(route('citizen.reports.store'), [
            'title' => 'Laporan Ekspor Dokumen',
            'description' => 'Pengujian ekspor berkas dan data.',
            'category' => 'Infrastruktur',
            'department_id' => $this->department->id,
            'priority' => 'low',
            'attachments' => [$fakePdf],
        ]);

        /** @var Report $report */
        $report = Report::where('user_id', $this->citizen->id)->latest()->first();

        // CSV Download by Admin
        $csvResponse = $this->actingAs($this->admin)
            ->get(route('reports.download_csv', $report->id));
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString($report->ticket_no, $csvResponse->getContent() ?: '');

        // Administration Download ZIP by Staff
        $zipResponse = $this->actingAs($this->staff)
            ->get(route('administration.reports.download', $report->id));
        $zipResponse->assertStatus(200);
    }
}
