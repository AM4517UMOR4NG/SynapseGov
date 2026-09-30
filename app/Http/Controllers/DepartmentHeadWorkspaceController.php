<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentHeadWorkspaceController extends Controller
{
    private function perPage(): int
    {
        $size = (int) auth()->user()->getSettings('items_per_page', 15);

        return in_array($size, [10, 15, 20, 25, 50], true) ? $size : 15;
    }

    private function reportsQuery()
    {
        $user = auth()->user();

        return Report::query()->where(function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
            if ($user->department_id) {
                $query->orWhere('department_id', $user->department_id);
            }
        });
    }

    private function teamQuery()
    {
        return User::where('department_id', auth()->user()->department_id)
            ->whereRaw(auth()->user()->department_id ? '1 = 1' : '1 = 0')
            ->whereIn('role', ['staff', 'department_head']);
    }

    public function index()
    {
        $reports = $this->reportsQuery();
        $counts = (clone $reports)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $metrics = [
            'total' => $counts->sum(),
            'assign' => (int) $counts->get('verified', 0),
            'active' => collect(['assigned', 'in_progress', 'reviewed', 'needs_revision', 'awaiting_info'])->sum(fn ($status) => (int) $counts->get($status, 0)),
            'done' => (int) $counts->get('resolved', 0) + (int) $counts->get('closed', 0),
            'overdue' => (clone $reports)->whereNotIn('status', ['resolved', 'closed', 'rejected'])->where('sla_due_at', '<', now())->count(),
        ];
        $recentReports = (clone $reports)->with(['user', 'assignedUser'])->latest()->limit(5)->get();
        $team = $this->teamQuery()->where('role', 'staff')->where('is_active', true)
            ->withCount(['assignedReports as active_reports_count' => fn ($q) => $q->whereNotIn('status', ['resolved', 'closed', 'rejected'])])
            ->orderByDesc('active_reports_count')->limit(5)->get();

        return view('administration.head.dashboard', compact('metrics', 'recentReports', 'team'));
    }

    public function tickets(Request $request, string $type)
    {
        $request->validate(['q' => 'nullable|string|max:150', 'status' => 'nullable|string|max:40', 'priority' => 'nullable|in:low,medium,high,urgent', 'overdue' => 'nullable|in:1']);
        $isReport = $type === 'reports';
        $query = $isReport ? $this->reportsQuery() : Complaint::where('department_id', auth()->user()->department_id)
            ->whereRaw(auth()->user()->department_id ? '1 = 1' : '1 = 0');
        $statusCounts = (clone $query)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $query->when($request->filled('q'), function ($query) use ($request) {
            $term = '%'.$request->input('q').'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('ticket_no', 'like', $term)
                ->orWhereHas('user', fn ($user) => $user->where('name', 'like', $term)));
        })->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->input('priority')))
            ->when($request->boolean('overdue'), fn ($q) => $q->whereNotIn('status', ['resolved', 'closed', 'rejected'])->where('sla_due_at', '<', now()));
        $tickets = $query->with(['user', 'assignedUser', 'department'])->latest()->paginate($this->perPage())->appends(request()->query());
        $staffList = $this->teamQuery()->where('role', 'staff')->where('is_active', true)->orderBy('name')->get();

        return view('administration.head.tickets', compact('tickets', 'type', 'isReport', 'statusCounts', 'staffList'));
    }

    public function staff(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:150', 'active' => 'nullable|in:0,1']);
        $query = $this->teamQuery();
        $total = (clone $query)->count();
        $active = (clone $query)->where('is_active', true)->count();
        $staff = $query->with('department')->withCount([
            'assignedReports as active_reports_count' => fn ($q) => $q->whereNotIn('status', ['resolved', 'closed', 'rejected']),
            'assignedComplaints as active_complaints_count' => fn ($q) => $q->whereNotIn('status', ['resolved', 'closed', 'rejected']),
        ])->when($request->filled('q'), function ($q) use ($request) {
            $term = '%'.$request->input('q').'%';
            $q->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
        })->when($request->filled('active'), fn ($q) => $q->where('is_active', $request->input('active')))
            ->orderBy('name')->paginate($this->perPage())->appends(request()->query());

        return view('administration.head.staff', compact('staff', 'total', 'active'));
    }
}
