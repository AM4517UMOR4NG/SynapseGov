<!-- Assign to Staff Modal -->
<div class="modal fade" id="assignStaffModal{{ $report->id }}" tabindex="-1" aria-labelledby="assignStaffModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignStaffModalLabel{{ $report->id }}">Tugaskan laporan kepada staf</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route(auth()->user()->isDepartmentHead() ? 'administration.reports.assign' : 'workflow.reports.admin_assign_staff', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to{{ $report->id }}" class="form-label">Pilih Staff:</label>
                        <select class="form-select" id="assigned_to{{ $report->id }}" name="assigned_to" required>
                            <option value="">-- Pilih Staff --</option>
                            @php 
                                // Get all staff directly
                                $allStaff = $staffList ?? \App\Models\User::where('role', 'staff')->where('is_active', true)
                                    ->when(auth()->user()->isDepartmentHead(), fn ($query) => $query->where('department_id', auth()->user()->department_id))
                                    ->with('department')->get();
                            @endphp
                            
                            @if($allStaff->count() > 0)
                                @foreach($allStaff as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->department->name ?? 'No Department' }})</option>
                                @endforeach
                            @else
                                <option value="" disabled>-- Tidak ada staff tersedia --</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes{{ $report->id }}" class="form-label">Catatan (Opsional):</label>
                        <textarea class="form-control" id="notes{{ $report->id }}" name="notes" rows="3" placeholder="Tambahkan catatan untuk staff..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @disabled($allStaff->isEmpty())>Simpan penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>
