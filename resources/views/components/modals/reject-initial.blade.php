<!-- Reject Initial Modal (Admin rejects report during verification) -->
<div class="modal fade" id="rejectInitialModal{{ $report->id }}" tabindex="-1" aria-labelledby="rejectInitialModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectInitialModalLabel{{ $report->id }}">
                    <i class="fas fa-times-circle text-danger me-2"></i>Tolak Laporan (Tidak Layak)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.reject', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Laporan akan ditolak dan status diubah ke <strong>Ditolak (rejected)</strong>. Alasan penolakan akan dikirimkan kepada pelapor.
                    </div>
                    <div class="mb-3">
                        <label for="reject_reason{{ $report->id }}" class="form-label">Alasan Penolakan:</label>
                        <textarea class="form-control" id="reject_reason{{ $report->id }}" name="reason" rows="4" placeholder="Jelaskan alasan mengapa laporan ini tidak layak diproses..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Tolak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
