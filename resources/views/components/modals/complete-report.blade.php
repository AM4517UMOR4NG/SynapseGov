<!-- Complete Report Modal (Staff submits work results and evidence to Admin) -->
<div class="modal fade" id="completeModal{{ $report->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel{{ $report->id }}">
                    <i class="fas fa-clipboard-check text-success me-2"></i>Ajukan Hasil & Bukti Pengerjaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.staff_confirm_admin', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-1"></i>
                        Laporan akan diserahkan ke Admin Utama dengan status <strong>Menunggu Persetujuan Admin (awaiting_admin_approval)</strong>.
                    </div>
                    <div class="mb-3">
                        <label for="completion_notes{{ $report->id }}" class="form-label">Catatan Hasil Pengerjaan:</label>
                        <textarea class="form-control" id="completion_notes{{ $report->id }}" name="completion_notes" rows="4" placeholder="Jelaskan secara rinci tindakan perbaikan yang telah dilakukan di lapangan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachments{{ $report->id }}" class="form-label">Foto / Dokumen Bukti (Opsional):</label>
                        <input type="file" class="form-control" id="attachments{{ $report->id }}" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                        <small class="text-muted">Dapat mengunggah foto sebelum/sesudah perbaikan (JPG, PNG, PDF, maks 5MB per file).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i>Ajukan ke Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
