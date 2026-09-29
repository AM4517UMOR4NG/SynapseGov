@extends('layouts.dashboard')

@section('title', 'Buat Laporan Baru')

@push('styles')
<style>
    /* Clean & Minimalist Form Styling */
    .form-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .dark .form-label {
        color: #e2e8f0 !important;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid var(--card-border) !important;
        background-color: var(--bg-canvas) !important;
        color: var(--text-main) !important;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control::placeholder, textarea::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.8 !important;
    }

    .dark .form-control, .dark .form-select {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    .dark .form-control::placeholder, .dark textarea::placeholder {
        color: #94a3b8 !important;
        opacity: 1 !important;
        -webkit-text-fill-color: #94a3b8 !important;
    }

    .dark .form-select option {
        background-color: #1e293b !important;
        color: #f8fafc !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary) !important;
        box-shadow: 0 0 0 3px rgba(183, 28, 28, 0.15) !important;
    }

    .dark .form-control:focus, .dark .form-select:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25) !important;
    }
    
    /* Neat File Upload Zone */
    .file-upload-wrapper {
        border: 2px dashed rgba(183, 28, 28, 0.3);
        background: rgba(183, 28, 28, 0.02);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .file-upload-wrapper:hover {
        background: rgba(183, 28, 28, 0.05);
        border-color: var(--brand-primary);
    }
    .dark .file-upload-wrapper {
        border: 2px dashed rgba(239, 68, 68, 0.4);
        background: rgba(239, 68, 68, 0.04);
    }
    .dark .file-upload-wrapper:hover {
        background: rgba(239, 68, 68, 0.08);
        border-color: #ef4444;
    }
    .dark .upload-content h6 {
        color: #f8fafc !important;
    }
    .dark .upload-content p {
        color: #94a3b8 !important;
    }
    .file-upload-input {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    /* Neat Guide Card */
    .guide-card {
        position: sticky;
        top: 68px;
        border-radius: 16px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
    }

    .dark .guide-card {
        background-color: #1e293b !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    .guide-list li {
        margin-bottom: 1rem;
        color: var(--text-muted);
        font-size: 0.9rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .dark .guide-list li {
        color: #cbd5e1 !important;
    }

    .dark .guide-list li strong {
        color: #f8fafc !important;
    }

    .guide-list i {
        color: var(--brand-primary);
        margin-top: 0.25rem;
    }

    .dark .guide-list i {
        color: #f87171 !important;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-header-modern d-flex justify-content-between align-items-center">
            <div>
                <h1>Buat Laporan Baru</h1>
                <p>Sampaikan laporan Anda dengan detail dan akurat.</p>
            </div>
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-header-back d-none d-md-flex align-items-center rounded-pill px-3 py-1">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('citizen.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    
                    <h5 class="fw-bold mb-4 pb-3 border-bottom text-primary-emphasis" style="color: var(--brand-primary) !important;">
                        <i class="fas fa-file-alt me-2"></i>Formulir Laporan
                    </h5>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">Judul Laporan Singkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Jalan berlubang di Jl. Merdeka" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <option value="Infrastruktur" {{ old('category') == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="Lingkungan" {{ old('category') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                <option value="Keamanan" {{ old('category') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="Kesehatan" {{ old('category') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Pendidikan" {{ old('category') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="" disabled selected>Pilih Tingkat Urgensi...</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="department_id" class="form-label">Instansi Tujuan (OPD) <span class="text-danger">*</span></label>
                        <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                            <option value="" disabled selected>Pilih Instansi Penanggung Jawab...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} ({{ $department->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">Lokasi Kejadian Lengkap</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}" placeholder="Detail lokasi, patokan, atau alamat lengkap">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Kronologi / Deskripsi Laporan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" placeholder="Ceritakan detail masalah yang terjadi..." 
                                  style="height: 120px; resize: vertical;" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lampiran Bukti (Opsional)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" class="file-upload-input @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple 
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <div class="upload-content">
                                <i class="fas fa-cloud-upload-alt mb-2 fs-3" style="color: var(--brand-primary);"></i>
                                <h6 class="fw-bold mb-1">Unggah Berkas Pendukung</h6>
                                <p class="text-muted small mb-0">Klik atau tarik berkas ke area ini (Maks. 5MB per berkas)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            Kirim Laporan <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="guide-card p-4 shadow-sm">
            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--brand-primary);">
                <i class="fas fa-lightbulb me-2"></i>Panduan Pelaporan
            </h6>
            <ul class="list-unstyled guide-list mb-0">
                <li>
                    <i class="fas fa-check-circle"></i>
                    <div><strong>Lugas:</strong> Buat judul yang langsung menjelaskan inti masalah (misal: "Lampu Jalan Mati").</div>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <div><strong>Akurat:</strong> Sertakan detail lokasi yang jelas untuk memudahkan petugas lapangan.</div>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <div><strong>Bukti Kuat:</strong> Laporan dengan lampiran foto akan ditindaklanjuti lebih cepat.</div>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <div><strong>Relevan:</strong> Pilih kategori dan instansi yang paling sesuai dengan masalah Anda.</div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('.file-upload-input');
        const uploadText = document.querySelector('.upload-content h6');
        const uploadIcon = document.querySelector('.upload-content i');
        
        if(fileInput && uploadText) {
            fileInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    uploadText.textContent = this.files.length + " Berkas Dipilih";
                    uploadText.classList.add('text-success');
                    uploadIcon.className = 'fas fa-check-circle text-success mb-2 fs-3';
                } else {
                    uploadText.textContent = "Unggah Berkas Pendukung";
                    uploadText.classList.remove('text-success');
                    uploadIcon.className = 'fas fa-cloud-upload-alt mb-2 fs-3';
                    uploadIcon.style.color = 'var(--brand-primary)';
                }
            });
        }
    });
</script>
@endpush
