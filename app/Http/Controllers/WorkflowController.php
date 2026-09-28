<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Services\WorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    protected WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Get the authenticated user.
     */
    protected function user(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        return $user;
    }

    /**
     * Check if user is authorized to manage the given report.
     */
    protected function canManageReport(User $user, Report $report): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDepartmentHead() && (int) $user->department_id === (int) $report->department_id) {
            return true;
        }

        return false;
    }

    /**
     * Verify a report (Admin or Department Head)
     */
    public function verifyReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak. Hanya admin atau kepala departemen terkait yang dapat memverifikasi laporan.');
        }

        if (! in_array($report->status, ['submitted', 'pending'])) {
            return redirect()->back()->with('error', 'Laporan dengan status "'.$report->status.'" tidak dapat diverifikasi kembali.');
        }

        $request->validate([
            'category' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'department_id' => 'sometimes|exists:departments,id',
        ]);

        $this->workflowService->verifyReport($report, $user, $request->only([
            'category', 'priority', 'department_id',
        ]));

        return redirect()->back()->with('success', 'Report verified successfully.');
    }

    /**
     * Reject a report (Admin or Department Head)
     */
    public function rejectReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak. Hanya admin atau kepala departemen terkait yang dapat menolak laporan.');
        }

        if (! in_array($report->status, ['submitted', 'pending', 'verified'])) {
            return redirect()->back()->with('error', 'Laporan dengan status "'.$report->status.'" tidak dapat ditolak.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $this->workflowService->rejectReport($report, $user, (string) $request->input('reason'));

        return redirect()->back()->with('success', 'Report rejected successfully.');
    }

    /**
     * Assign a report to staff (Admin or Department Head)
     */
    public function assignReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak. Hanya admin atau kepala departemen terkait yang dapat menugaskan laporan.');
        }

        if (in_array($report->status, ['closed', 'rejected'])) {
            return redirect()->back()->with('error', 'Laporan yang telah ditutup atau ditolak tidak dapat ditugaskan.');
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $assignedTo = User::findOrFail($request->input('assigned_to'));

        if (! ($assignedTo->isStaff() || $assignedTo->isDepartmentHead() || $assignedTo->isAdmin())) {
            return redirect()->back()->with('error', 'Laporan hanya dapat ditugaskan kepada staf atau kepala departemen.');
        }

        if (! $assignedTo->is_active) {
            return redirect()->back()->with('error', 'Petugas yang dipilih sedang tidak aktif.');
        }

        if ($report->department_id && $assignedTo->department_id && (int) $assignedTo->department_id !== (int) $report->department_id && ! $assignedTo->isAdmin()) {
            return redirect()->back()->with('error', 'Petugas yang dipilih tidak berada di departemen yang sesuai dengan laporan.');
        }

        $this->workflowService->assignReport($report, $assignedTo, $user, $request->input('notes'));

        return redirect()->back()->with('success', 'Report assigned successfully.');
    }

    /**
     * Start working on a report (Assigned staff)
     */
    public function startWork($id): RedirectResponse
    {
        $report = Report::findOrFail($id);
        $user = $this->user();

        // Check if user is assigned to this report
        if ((int) $report->assigned_to !== (int) $user->id && ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Anda tidak ditugaskan untuk laporan ini.');
        }

        if (! in_array($report->status, ['assigned', 'reviewed', 'needs_revision'])) {
            return redirect()->back()->with('error', 'Pengerjaan tidak dapat dimulai untuk laporan berstatus "'.$report->status.'".');
        }

        $this->workflowService->startWork($report, $user);

        return redirect()->back()->with('success', 'Started working on report.');
    }

    /**
     * Add a comment to a report
     */
    public function addComment(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        $isInternal = false;

        if ($user->isCitizen()) {
            if ((int) $report->user_id !== (int) $user->id) {
                abort(403, 'Akses ditolak. Anda tidak berhak mengomentari laporan ini.');
            }
            // Citizens cannot add internal comments
            $isInternal = false;
        } else {
            // Verify staff/head has rights to this report's department
            if (! $user->isAdmin() && (int) $report->department_id !== (int) $user->department_id && (int) $report->assigned_to !== (int) $user->id) {
                abort(403, 'Akses ditolak. Anda tidak berwenang mengomentari laporan dari departemen lain.');
            }
            $isInternal = $request->boolean('is_internal');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
            'is_internal' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/comments');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $this->workflowService->addComment(
            $report,
            $user,
            (string) $request->input('content'),
            $isInternal,
            $attachments
        );

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Set report to awaiting info
     */
    public function setAwaitingInfo(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        // Must be assigned staff, department head, or admin
        if (! ($user->isAdmin() || (int) $report->assigned_to === (int) $user->id || ($user->isDepartmentHead() && (int) $user->department_id === (int) $report->department_id))) {
            abort(403, 'Akses ditolak.');
        }

        if (! in_array($report->status, ['in_progress', 'assigned'])) {
            return redirect()->back()->with('error', 'Laporan dengan status "'.$report->status.'" tidak dapat menunggu informasi.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $this->workflowService->setAwaitingInfo($report, $user, (string) $request->input('reason'));

        return redirect()->back()->with('success', 'Report set to awaiting information.');
    }

    /**
     * Resolve a report
     */
    public function resolveReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        // Must be assigned staff, department head, or admin
        if (! ($user->isAdmin() || (int) $report->assigned_to === (int) $user->id || ($user->isDepartmentHead() && (int) $user->department_id === (int) $report->department_id))) {
            abort(403, 'Akses ditolak. Anda tidak berhak menyelesaikan laporan ini.');
        }

        if (! in_array($report->status, ['in_progress', 'assigned', 'needs_revision', 'awaiting_info'])) {
            return redirect()->back()->with('error', 'Laporan dengan status "'.$report->status.'" tidak dapat diselesaikan.');
        }

        $request->validate([
            'resolution_notes' => 'required|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/resolutions');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $this->workflowService->resolveReport($report, $user, (string) $request->input('resolution_notes'), $attachments);

        return redirect()->back()->with('success', 'Report resolved successfully.');
    }

    /**
     * Approve a resolved report (Admin or Department Head)
     */
    public function approveReport($id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Hanya admin atau kepala departemen yang dapat menyetujui laporan.');
        }

        if (! in_array($report->status, ['resolved', 'awaiting_admin_approval'])) {
            return redirect()->back()->with('error', 'Hanya laporan yang telah diselesaikan yang dapat disetujui.');
        }

        $this->workflowService->approveReport($report, $user);

        return redirect()->back()->with('success', 'Report approved and closed.');
    }

    /**
     * Request changes to a resolved report
     */
    public function requestChanges(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak.');
        }

        if (! in_array($report->status, ['resolved', 'awaiting_admin_approval'])) {
            return redirect()->back()->with('error', 'Perubahan hanya dapat diminta pada laporan yang telah diselesaikan.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $this->workflowService->requestChanges($report, $user, (string) $request->input('reason'));

        return redirect()->back()->with('success', 'Changes requested successfully.');
    }

    /**
     * Reopen a closed report
     */
    public function reopenReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if ($user->isCitizen() && (int) $report->user_id !== (int) $user->id) {
            abort(403, 'Anda hanya dapat membuka kembali laporan milik Anda sendiri.');
        }

        if (! $user->isCitizen() && ! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $this->workflowService->reopenReport($report, $user, (string) $request->input('reason'));

            return redirect()->back()->with('success', 'Report reopened successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reassign a report (Admin or Department Head)
     */
    public function reassignReport(Request $request, $id): RedirectResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        if (! $this->canManageReport($user, $report)) {
            abort(403, 'Akses ditolak.');
        }

        if (in_array($report->status, ['closed', 'rejected'])) {
            return redirect()->back()->with('error', 'Laporan yang telah ditutup atau ditolak tidak dapat dialihkan.');
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $newAssignee = User::findOrFail($request->input('assigned_to'));

        if (! ($newAssignee->isStaff() || $newAssignee->isDepartmentHead() || $newAssignee->isAdmin())) {
            return redirect()->back()->with('error', 'Laporan hanya dapat dialihkan kepada staf atau kepala departemen.');
        }

        if (! $newAssignee->is_active) {
            return redirect()->back()->with('error', 'Petugas yang dipilih sedang tidak aktif.');
        }

        if ($report->department_id && $newAssignee->department_id && (int) $newAssignee->department_id !== (int) $report->department_id && ! $newAssignee->isAdmin()) {
            return redirect()->back()->with('error', 'Petugas yang dipilih tidak berada di departemen yang sesuai dengan laporan.');
        }

        $this->workflowService->reassignReport($report, $newAssignee, $user, $request->input('reason'));

        return redirect()->back()->with('success', 'Report reassigned successfully.');
    }

    /**
     * Get report workflow history (filtered by privacy)
     */
    public function getWorkflowHistory($id): JsonResponse
    {
        $user = $this->user();
        $report = Report::findOrFail($id);

        // Authorization check
        if ($user->isCitizen() && (int) $report->user_id !== (int) $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (! $user->isCitizen() && ! $user->isAdmin()) {
            if ($report->department_id && (int) $user->department_id !== (int) $report->department_id && (int) $report->assigned_to !== (int) $user->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        $auditLogs = $report->auditLogs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Privacy filter: Citizens must NEVER see internal comments
        $commentsQuery = $report->comments()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($user->isCitizen()) {
            $commentsQuery->where('is_internal', false);
        }

        $comments = $commentsQuery->get();

        $assignments = $report->assignments()
            ->with(['assignedTo', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'audit_logs' => $auditLogs,
            'comments' => $comments,
            'assignments' => $assignments,
        ]);
    }
}
