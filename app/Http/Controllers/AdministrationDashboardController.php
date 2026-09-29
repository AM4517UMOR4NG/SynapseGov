<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdministrationDashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isDepartmentHead()) {
            return app(DepartmentHeadWorkspaceController::class)->index();
        }
        $user = Auth::user();
        $department = $user->department;

        if (!$department) {
            return redirect()->route('home')->with('error', 'Akun Anda belum terhubung dengan departemen manapun.');
        }

        // Statistik departemen
        if ($user->role === 'department_head') {
            $repStat = Report::where('department_id', $department->id)
                ->selectRaw('
                    COUNT(*) as total_reports,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending") THEN 1 ELSE 0 END), 0) as pending_reports,
                    COALESCE(SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END), 0) as in_progress_reports,
                    COALESCE(SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END), 0) as resolved_reports,
                    COALESCE(SUM(CASE WHEN date(created_at) = date("now") THEN 1 ELSE 0 END), 0) as today_reports,
                    COALESCE(SUM(CASE WHEN date(resolved_at) = date("now") THEN 1 ELSE 0 END), 0) as completed_today,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending", "verified") THEN 1 ELSE 0 END), 0) as pending_action
                ')->first();

            $compStat = Complaint::where('department_id', $department->id)
                ->selectRaw('
                    COUNT(*) as total_complaints,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending") THEN 1 ELSE 0 END), 0) as pending_complaints,
                    COALESCE(SUM(CASE WHEN status = "investigating" THEN 1 ELSE 0 END), 0) as investigating_complaints,
                    COALESCE(SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END), 0) as resolved_complaints
                ')->first();
        } else {
            // Statistik untuk staff - hanya laporan yang ditugaskan kepada mereka
            $repStat = Report::where('assigned_to', $user->id)
                ->selectRaw('
                    COUNT(*) as total_reports,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending") THEN 1 ELSE 0 END), 0) as pending_reports,
                    COALESCE(SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END), 0) as in_progress_reports,
                    COALESCE(SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END), 0) as resolved_reports,
                    COALESCE(SUM(CASE WHEN date(created_at) = date("now") THEN 1 ELSE 0 END), 0) as today_reports,
                    COALESCE(SUM(CASE WHEN date(resolved_at) = date("now") THEN 1 ELSE 0 END), 0) as completed_today,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending", "verified") THEN 1 ELSE 0 END), 0) as pending_action
                ')->first();

            $compStat = Complaint::where('assigned_to', $user->id)
                ->selectRaw('
                    COUNT(*) as total_complaints,
                    COALESCE(SUM(CASE WHEN status IN ("submitted", "pending") THEN 1 ELSE 0 END), 0) as pending_complaints,
                    COALESCE(SUM(CASE WHEN status = "investigating" THEN 1 ELSE 0 END), 0) as investigating_complaints,
                    COALESCE(SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END), 0) as resolved_complaints
                ')->first();
        }

        $stats = [
            'total_reports' => (int) ($repStat->total_reports ?? 0),
            'pending_reports' => (int) ($repStat->pending_reports ?? 0),
            'in_progress_reports' => (int) ($repStat->in_progress_reports ?? 0),
            'resolved_reports' => (int) ($repStat->resolved_reports ?? 0),
            'total_complaints' => (int) ($compStat->total_complaints ?? 0),
            'pending_complaints' => (int) ($compStat->pending_complaints ?? 0),
            'investigating_complaints' => (int) ($compStat->investigating_complaints ?? 0),
            'resolved_complaints' => (int) ($compStat->resolved_complaints ?? 0),
            'today_reports' => (int) ($repStat->today_reports ?? 0),
            'completed_today' => (int) ($repStat->completed_today ?? 0),
            'pending_action' => (int) ($repStat->pending_action ?? 0),
        ];

        // Laporan departemen
        if ($user->role === 'department_head') {
            $departmentReports = Report::with(['user', 'assignedUser'])
                ->where(function ($q) use ($department, $user) {
                    $q->where('department_id', $department->id)
                        ->orWhere('assigned_to', $user->id);
                })
                ->latest()
                ->limit(10)
                ->get();
        } else {
            // Staff melihat laporan yang ditugaskan kepada mereka + laporan departemen
            $departmentReports = Report::with(['user', 'assignedUser'])
                ->where(function ($query) use ($user, $department) {
                    $query->where('department_id', $department->id)
                        ->orWhere('assigned_to', $user->id);
                })
                ->latest()
                ->limit(10)
                ->get();
        }

        // Keluhan departemen
        $departmentComplaints = Complaint::with(['user', 'assignedUser'])
            ->where('department_id', $department->id)
            ->latest()
            ->limit(10)
            ->get();

        // Staff departemen
        $departmentStaff = User::where('department_id', $department->id)
            ->where('role', '!=', 'citizen')
            ->get();

        return view('administration.modern-dashboard', compact(
            'stats',
            'department',
            'departmentReports',
            'departmentComplaints',
            'departmentStaff'
        ));
    }

    public function reports()
    {
        if (Auth::user()->isDepartmentHead()) {
            return app(DepartmentHeadWorkspaceController::class)->tickets(request(), 'reports');
        }
        $user = Auth::user();
        $perPage = $user->getSettings('items_per_page', 15);

        // Laporan yang dapat diakses oleh Kepala Departemen atau Staff
        $reports = Report::with(['user', 'assignedUser'])
            ->where(function ($q) use ($user) {
                if ($user->department_id) {
                    $q->where('department_id', $user->department_id)
                        ->orWhere('assigned_to', $user->id);
                } else {
                    $q->where('assigned_to', $user->id);
                }
            })
            ->latest()
            ->paginate($perPage);

        // Ambil daftar staff untuk assignment dropdown
        $staffList = User::where('department_id', $user->department_id)
            ->where('role', 'staff')
            ->where('id', '!=', $user->id)
            ->get();

        return view('administration.reports', compact('reports', 'staffList'));
    }

    public function complaints()
    {
        if (Auth::user()->isDepartmentHead()) {
            return app(DepartmentHeadWorkspaceController::class)->tickets(request(), 'complaints');
        }
        $user = Auth::user();
        $perPage = $user->getSettings('items_per_page', 15);
        $complaints = Complaint::with(['user', 'assignedUser'])
            ->where('department_id', $user->department_id)
            ->latest()
            ->paginate($perPage);

        $staffList = User::where('department_id', $user->department_id)
            ->where('role', 'staff')
            ->where('is_active', true)
            ->get();

        return view('administration.complaints', compact('complaints', 'staffList'));
    }

    public function staff()
    {
        if (Auth::user()->isDepartmentHead()) {
            return app(DepartmentHeadWorkspaceController::class)->staff(request());
        }
        $user = Auth::user();
        $perPage = $user->getSettings('items_per_page', 15);
        $staff = User::where('department_id', $user->department_id)
            ->where('role', '!=', 'citizen')
            ->paginate($perPage);

        return view('administration.staff', compact('staff'));
    }

    public function assignReport(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['department_head', 'staff'])) {
            abort(403, 'Anda tidak berhak menugaskan laporan ini.');
        }

        $request->validate([
            'assigned_to' => [
                'required',
                'exists:users,id',
                \Illuminate\Validation\Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    $query->where('department_id', $user->department_id)->where('is_active', true);
                }),
            ],
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($id, $request, $user) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            $assignedTo = User::findOrFail($request->assigned_to);

            $workflowService = app(\App\Services\WorkflowService::class);
            $workflowService->assignReport($report, $assignedTo, $user, $request->notes);

            return redirect()->back()->with('success', 'Laporan berhasil ditugaskan ke ' . $assignedTo->name);
        });
    }

    public function assignComplaint(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['department_head', 'staff'])) {
            abort(403, 'Anda tidak berhak menugaskan keluhan ini.');
        }

        $request->validate([
            'assigned_to' => [
                'required',
                'exists:users,id',
                \Illuminate\Validation\Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    $query->where('department_id', $user->department_id)->where('is_active', true);
                }),
            ],
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($id, $request, $user) {
            $complaint = Complaint::where('department_id', $user->department_id)->lockForUpdate()->findOrFail($id);

            $assignedTo = User::findOrFail($request->assigned_to);

            \App\Models\Assignment::where('assignable_type', Complaint::class)
                ->where('assignable_id', $complaint->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'reassigned',
                    'completed_at' => now(),
                ]);

            \App\Models\Assignment::create([
                'assignable_id' => $complaint->id,
                'assignable_type' => Complaint::class,
                'assigned_to' => $assignedTo->id,
                'assigned_by' => $user->id,
                'notes' => $request->notes,
                'assigned_at' => now(),
                'status' => 'active',
            ]);

            $oldAssigned = $complaint->assigned_to;
            $complaint->update([
                'assigned_to' => $assignedTo->id,
                'status' => 'investigating',
                'last_activity_at' => now(),
            ]);

            AuditLog::create([
                'auditable_type' => Complaint::class,
                'auditable_id' => $complaint->id,
                'user_id' => $user->id,
                'event' => 'complaint_assigned',
                'old_values' => ['assigned_to' => $oldAssigned],
                'new_values' => ['assigned_to' => $assignedTo->id, 'status' => 'investigating'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Keluhan berhasil ditugaskan ke ' . $assignedTo->name);
        });
    }

    public function downloadReport($id)
    {
        $user = Auth::user();
        $report = Report::with(['user', 'department', 'assignedUser'])
            ->where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })
            ->findOrFail($id);

        if (!class_exists('ZipArchive')) {
            return back()->with('error', 'Ekstensi ZipArchive PHP tidak terpasang di server.');
        }

        $zip = new \ZipArchive;
        $zipPath = storage_path('app/temp/report_' . $report->id . '_' . time() . '.zip');
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat arsip unduhan.');
        }

        $metadata = [
            'id' => $report->id,
            'title' => $report->title,
            'description' => $report->description,
            'category' => $report->category,
            'status' => $report->status,
            'priority' => $report->priority,
            'department' => optional($report->department)->name,
            'location' => $report->location,
            'created_at' => (string) $report->created_at,
            'updated_at' => (string) $report->updated_at,
            'user' => $report->user ? ['id' => $report->user->id, 'name' => $report->user->name, 'email' => $report->user->email] : null,
            'assigned_to' => $report->assignedUser ? ['id' => $report->assignedUser->id, 'name' => $report->assignedUser->name] : null,
        ];
        $zip->addFromString('report.json', json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if (is_array($report->attachments)) {
            foreach ($report->attachments as $relPath) {
                $normalized = str_replace('\\', '/', (string) $relPath);
                $cleanRel = str_starts_with($normalized, 'public/') ? substr($normalized, 7) : $normalized;
                $abs = storage_path('app/public/' . $cleanRel);
                if (!file_exists($abs)) {
                    $abs = storage_path('app/' . $normalized);
                }
                if (file_exists($abs)) {
                    $zip->addFile($abs, 'attachments/' . basename($relPath));
                }
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function confirmReport($id)
    {
        $user = Auth::user();
        // Hanya Kepala Departemen atau Staff terkait yang boleh konfirmasi
        if (!in_array($user->role, ['department_head', 'staff'])) {
            abort(403);
        }

        return DB::transaction(function () use ($id, $user) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            if ($user->role === 'staff' && (int) $report->assigned_to !== (int) $user->id && (int) $report->department_id !== (int) $user->department_id) {
                abort(403);
            }

            // Gunakan WorkflowService agar: set status, generate queue_no (jika belum), dan trigger event/notification
            $workflow = app(\App\Services\WorkflowService::class);
            $workflow->verifyReport($report, $user);

            return back()->with('success', 'Laporan berhasil dikonfirmasi. Nomor antrian: ' . ($report->queue_no ?? '-'));
        });
    }

    public function sendReportToHead($id)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($id, $user) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            // Pastikan laporan sudah dikonfirmasi terlebih dahulu
            if (!in_array($report->status, ['verified', 'in_progress', 'assigned', 'needs_revision'])) {
                return back()->with('error', 'Laporan harus dikonfirmasi terlebih dahulu sebelum diteruskan ke Kepala Departemen.');
            }

            // Hanya staff yang ditugaskan ATAU kepala departemen yang boleh meneruskan
            if ($user->role === 'staff' && (int) $report->assigned_to !== (int) $user->id && (int) $report->department_id !== (int) $user->department_id) {
                return back()->with('error', 'Anda tidak berhak meneruskan laporan ini.');
            }

            $targetDeptId = $report->department_id ?: $user->department_id;
            $head = User::where('role', 'department_head')->where('department_id', $targetDeptId)->first();
            if (!$head) {
                return back()->with('error', 'Tidak ditemukan kepala departemen.');
            }

            // Gunakan WorkflowService agar Assignment dibuat dan event ditrigger
            $workflow = app(\App\Services\WorkflowService::class);
            $workflow->assignReport($report, $head, $user, 'Diteruskan ke Kepala Departemen');

            // Log audit
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'user_id' => $user->id,
                'event' => 'forwarded_to_head',
                'old_values' => null,
                'new_values' => ['assigned_to' => $head->id, 'status' => 'assigned'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Laporan berhasil diteruskan ke Kepala Departemen.');
        });
    }

    /**
     * Confirm (if needed) and forward the report to the head in one step.
     */
    public function confirmAndSend($id)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($id, $user) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            // Authorization: assigned staff or department head
            if ($user->role === 'staff' && (int) $report->assigned_to !== (int) $user->id && (int) $report->department_id !== (int) $user->department_id) {
                return back()->with('error', 'Anda tidak berhak mengirim laporan ini.');
            }

            // Status guard
            $allowedStatuses = ['submitted', 'pending', 'verified', 'assigned', 'needs_revision'];
            if (!in_array($report->status, $allowedStatuses)) {
                return back()->with('error', 'Laporan dengan status "' . $report->status . '" tidak dapat dikirim ke Kepala Departemen.');
            }

            $workflow = app(\App\Services\WorkflowService::class);

            // If still submitted/pending, mark as verified first via WorkflowService
            if (in_array($report->status, ['submitted', 'pending'])) {
                $workflow->verifyReport($report, $user);

                // Audit: confirmed by staff/head
                AuditLog::create([
                    'auditable_type' => Report::class,
                    'auditable_id' => $report->id,
                    'user_id' => $user->id,
                    'event' => 'confirmed',
                    'old_values' => null,
                    'new_values' => ['status' => 'verified'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            $targetDeptId = $report->department_id ?: $user->department_id;
            $head = User::where('role', 'department_head')->where('department_id', $targetDeptId)->first();
            if (!$head) {
                return back()->with('error', 'Tidak ditemukan kepala departemen.');
            }

            // Gunakan WorkflowService agar Assignment dibuat dan event ditrigger
            $workflow->assignReport($report, $head, $user, 'Dikonfirmasi dan dikirim ke Kepala Departemen');

            // Audit: forwarded to head
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'user_id' => $user->id,
                'event' => 'forwarded_to_head',
                'old_values' => null,
                'new_values' => ['assigned_to' => $head->id, 'status' => 'assigned'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Laporan dikonfirmasi dan dikirim ke Kepala Departemen.');
        });
    }

    /**
     * Head returns a report to a selected staff after review.
     */
    public function returnToStaff(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            return back()->with('error', 'Hanya Kepala Departemen yang dapat mengembalikan laporan ke staff.');
        }

        $request->validate(['assigned_to' => 'required|integer|exists:users,id']);

        $assignedTo = User::findOrFail($request->assigned_to);
        if (!$assignedTo->isStaff()) {
            return back()->with('error', 'User yang dipilih bukan staff.');
        }

        return DB::transaction(function () use ($id, $user, $request, $assignedTo) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            // Status guard
            if (!in_array($report->status, ['assigned', 'in_progress', 'verified'])) {
                return back()->with('error', 'Laporan dengan status "' . $report->status . '" tidak dapat dikembalikan ke staff.');
            }

            $oldStatus = $report->status;

            // Gunakan WorkflowService agar assignment ditutup dan assignment baru dibuat
            $workflow = app(\App\Services\WorkflowService::class);
            $workflow->assignReport($report, $assignedTo, $user, $request->notes ?: 'Dikembalikan ke staff untuk tindak lanjut', 'reviewed');

            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'user_id' => $user->id,
                'event' => 'returned_to_staff',
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['assigned_to' => $assignedTo->id, 'status' => 'reviewed'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('success', 'Laporan dikembalikan ke staff untuk tindak lanjut.');
        });
    }

    /**
     * Staff confirms back to admin after completing actions.
     */
    public function confirmToAdmin($id)
    {
        $user = Auth::user();
        if ($user->role !== 'staff') {
            return back()->with('error', 'Hanya staff yang berhak mengonfirmasi laporan ini ke admin.');
        }

        return DB::transaction(function () use ($id, $user) {
            $report = Report::where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                    ->orWhere('assigned_to', $user->id);
            })->lockForUpdate()->findOrFail($id);

            if ((int) $report->assigned_to !== (int) $user->id && (int) $report->department_id !== (int) $user->department_id) {
                return back()->with('error', 'Anda tidak berhak mengonfirmasi laporan ini ke admin.');
            }

            // Status guard: only allow valid active statuses
            $allowedStatuses = ['reviewed', 'in_progress', 'assigned', 'needs_revision', 'verified'];
            if (!in_array($report->status, $allowedStatuses)) {
                return back()->with('error', 'Laporan dengan status "' . $report->status . '" tidak dapat dikonfirmasi ke admin.');
            }

            $oldStatus = $report->status;
            $oldAssignedTo = $report->assigned_to;
            $completionNotes = request('completion_notes', $report->completion_notes);

            // Tutup active assignments record
            $report->assignments()->where('status', 'active')->update([
                'status' => 'completed',
                'completed_at' => now(),
                'notes' => $completionNotes ?: 'Dikonfirmasi ke admin untuk persetujuan akhir',
            ]);

            $report->update([
                'assigned_to' => null,
                'status' => 'awaiting_admin_approval',
                'last_activity_at' => now(),
                'completion_notes' => $completionNotes,
            ]);

            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'user_id' => $user->id,
                'event' => 'confirmed_to_admin',
                'old_values' => ['assigned_to' => $oldAssignedTo, 'status' => $oldStatus],
                'new_values' => ['assigned_to' => null, 'status' => 'awaiting_admin_approval'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            event(new \App\Events\ReportStatusChanged($report, $oldStatus, 'awaiting_admin_approval', $user));

            return back()->with('success', 'Laporan telah dikonfirmasi ke admin untuk persetujuan akhir.');
        });
    }

    /**
     * Update report status (for staff and department head)
     */
    public function updateReport(Request $request, $id)
    {
        $user = Auth::user();
        $report = Report::findOrFail($id);

        // Authorization check
        if ($user->role === 'staff') {
            // Staff can update reports assigned to them or in their department
            if ((int) $report->assigned_to !== (int) $user->id && (int) $report->department_id !== (int) $user->department_id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'department_head') {
            // Department head can update reports in their department or assigned to them
            if ((int) $report->department_id !== (int) $user->department_id && (int) $report->assigned_to !== (int) $user->id) {
                abort(403, 'Unauthorized');
            }
        } else {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:submitted,pending,verified,assigned,in_progress,awaiting_info,resolved,closed,rejected,awaiting_admin_approval',
            'resolution_notes' => 'nullable|string|max:2000',
        ]);

        // Update status
        $oldStatus = $report->status;
        $report->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes ?? $report->resolution_notes,
            'last_activity_at' => now(),
        ]);

        // Mark as resolved if status is resolved
        if ($request->status === 'resolved' && !$report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }

        // Create audit log
        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'user_id' => $user->id,
            'event' => 'status_updated',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Fire status changed event
        if ($oldStatus !== $request->status) {
            event(new \App\Events\ReportStatusChanged($report, $oldStatus, $request->status, $user));
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'report' => $report]);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
