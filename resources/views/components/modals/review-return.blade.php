<!-- Review and Return Modal -->
<div class="modal fade" id="reviewReturnModal{{ $report->id }}" tabindex="-1" aria-labelledby="reviewReturnModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewReturnModalLabel{{ $report->id }}">Review & Kembalikan ke Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.head_review_return', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Laporan akan dikembalikan ke staff untuk tindak lanjut.
                    </div>
                    <div class="mb-3">
                        <label for="review_assigned_to{{ $report->id }}" class="form-label">Kembalikan ke Staff:</label>
                        <select class="form-select" id="review_assigned_to{{ $report->id }}" name="assigned_to" required>
                            <option value="">-- Pilih Staff --</option>
                            @php
                                $targetDeptId = $report->department_id ?: (auth()->check() ? auth()->user()->department_id : null);
                                $deptStaffList = \App\Models\User::where('role', 'staff')
                                    ->where('is_active', true)
                                    ->where('department_id', $targetDeptId)
                                    ->whereRaw($targetDeptId ? '1 = 1' : '1 = 0')
                                    ->get();
                            @endphp
                            @foreach($deptStaffList as $staff)
                                <option value="{{ $staff->id }}" {{ (int) $report->assigned_to === (int) $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ $staff->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="review_notes{{ $report->id }}" class="form-label">Catatan Review (Opsional):</label>
                        <textarea class="form-control" id="review_notes{{ $report->id }}" name="notes" rows="3" placeholder="Tambahkan catatan review..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @disabled($deptStaffList->isEmpty())>Review & Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
