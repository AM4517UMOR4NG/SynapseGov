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
}
