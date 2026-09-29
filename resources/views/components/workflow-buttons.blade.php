@props(['report', 'user', 'staffList' => null])

<div class="workflow-buttons mt-3">
    @if($user->isAdmin())
        {{-- Admin Actions --}}
        @if($report->status === 'submitted' || $report->status === 'pending')
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignStaffModal{{ $report->id }}">
                    <i class="fas fa-user-plus"></i> Assign ke Staff
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignHeadModal{{ $report->id }}">
                    <i class="fas fa-user-tie"></i> Kirim ke Head
                </button>
            </div>
        @elseif($report->status === 'awaiting_admin_approval')
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $report->id }}">
                    <i class="fas fa-check"></i> Approve & Close
                </button>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $report->id }}">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
        @endif

    @elseif($user->isStaff())
        {{-- Staff Actions --}}
        @php
            $isAssignedStaff = (int) $report->assigned_to === (int) $user->id;
            $isInDept = (int) $report->department_id === (int) $user->department_id;
            $canAct = $isAssignedStaff || $isInDept;
        @endphp
        @if($canAct && in_array($report->status, ['assigned', 'verified']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmForwardModal{{ $report->id }}">
                    <i class="fas fa-forward"></i> Konfirmasi & Kirim ke Head
                </button>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeModal{{ $report->id }}">
                    <i class="fas fa-check-circle"></i> Selesaikan & Kirim ke Admin
                </button>
            </div>
        @elseif($canAct && in_array($report->status, ['reviewed', 'in_progress', 'needs_revision']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeModal{{ $report->id }}">
                    <i class="fas fa-check-circle"></i> Selesaikan & Kirim ke Admin
                </button>
                @if($report->status === 'needs_revision')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmForwardModal{{ $report->id }}">
                    <i class="fas fa-forward"></i> Kirim Ulang ke Head
                </button>
                @endif
            </div>
        @endif

    @elseif($user->isDepartmentHead())
        {{-- Department Head Actions --}}
        @php
            $canHeadAct = ((int) $report->assigned_to === (int) $user->id) || ((int) $report->department_id === (int) $user->department_id);
        @endphp
        @if($canHeadAct && in_array($report->status, ['assigned', 'verified', 'in_progress']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewReturnModal{{ $report->id }}">
                    <i class="fas fa-undo"></i> Review & Kembalikan ke Staff
                </button>
            </div>
        @endif
    @endif

    {{-- Status Badge --}}
    <span class="badge 
        @if($report->status === 'submitted') bg-secondary
        @elseif($report->status === 'pending') bg-warning
        @elseif($report->status === 'verified') bg-info
        @elseif($report->status === 'assigned') bg-primary
        @elseif($report->status === 'in_progress') bg-warning
        @elseif($report->status === 'reviewed') bg-info
        @elseif($report->status === 'awaiting_admin_approval') bg-warning
        @elseif($report->status === 'resolved') bg-success
        @elseif($report->status === 'needs_revision') bg-danger
        @else bg-secondary
        @endif ms-2">
        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
    </span>
</div>

{{-- Modals for Admin (deferred to end of body to avoid stacking issues) --}}
@if($user->isAdmin())
    @push('modals')
        @include('components.modals.assign-staff', ['report' => $report, 'staffList' => $staffList])
        @include('components.modals.assign-head', ['report' => $report])
        @include('components.modals.approve-report', ['report' => $report])
        @include('components.modals.reject-report', ['report' => $report, 'staffList' => $staffList])
    @endpush
@endif

{{-- Modals for Staff (deferred) --}}
@if($user->isStaff())
    @push('modals')
        @include('components.modals.confirm-forward', ['report' => $report])
        @include('components.modals.complete-report', ['report' => $report])
    @endpush
@endif

{{-- Modals for Department Head (deferred) --}}
@if($user->isDepartmentHead())
    @push('modals')
        @include('components.modals.review-return', ['report' => $report])
    @endpush
@endif
