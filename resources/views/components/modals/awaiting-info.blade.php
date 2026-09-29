<!-- Awaiting Info Modal (Admin / Staff requests additional data from citizen) -->
<div class="modal fade" id="awaitingInfoModal{{ $report->id }}" tabindex="-1" aria-labelledby="awaitingInfoModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="awaitingInfoModalLabel{{ $report->id }}">
                    <i class="fas fa-question-circle text-warning me-2"></i>Minta Kelengkapan Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.awaiting_info', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Status laporan akan diubah ke <strong>Data Kurang (awaiting_info)</strong>. Pelapor akan diminta melengkapi data sebelum verifikasi dilanjutkan.
                    </div>
                    <div class="mb-3">
                        <label for="reason{{ $report->id }}" class="form-label">Data yang Kurang / Diperlukan:</label>
                        <textarea class="form-control" id="reason{{ $report->id }}" name="reason" rows="4" placeholder="Jelaskan secara rinci data atau foto bukti yang perlu dilengkapi pelapor..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Permintaan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
