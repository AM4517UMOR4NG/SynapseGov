@extends('layouts.dashboard')

@section('title', 'Keluhan Saya')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-modern d-flex justify-content-between align-items-center">
        <div>
            <h2>Keluhan Saya</h2>
            <p>Lihat dan kelola semua keluhan yang telah Anda ajukan.</p>
        </div>
        <a href="{{ route('citizen.complaints.create') }}" class="btn-clean btn-outline-clean">
            <i class="fas fa-plus"></i>
            <span>Ajukan Keluhan</span>
        </a>
    </div>

    @if($complaints->count() > 0)
        <!-- Table Header (Desktop) -->
        <div class="d-none d-md-grid complaint-grid-header mb-3 px-3">
            <div style="grid-column: span 3;">JUDUL</div>
            <div style="grid-column: span 2;">KATEGORI</div>
            <div style="grid-column: span 2;">DEPARTEMEN</div>
            <div style="grid-column: span 1; text-align: center;">STATUS</div>
            <div style="grid-column: span 2; text-align: center;">PRIORITAS</div>
            <div style="grid-column: span 1; text-align: center;">TANGGAL</div>
            <div style="grid-column: span 1; text-align: right;">AKSI</div>
        </div>

        <!-- Complaint Items -->
        <div class="complaint-list">
            @foreach($complaints as $complaint)
            <div class="complaint-item">
                <div class="complaint-grid">
                    <!-- Judul -->
                    <div class="complaint-title-col">
                        <p class="complaint-title">{{ $complaint->title }}</p>
                        <p class="complaint-desc">{{ Str::limit($complaint->description, 60) }}</p>
                    </div>
                    
                    <!-- Kategori -->
                    <div class="complaint-category-col">
                        <p class="complaint-text">{{ ucfirst($complaint->category) }}</p>
                    </div>
                    
                    <!-- Departemen -->
                    <div class="complaint-dept-col">
                        <p class="complaint-text">{{ $complaint->department->name }}</p>
                    </div>
                    
                    <!-- Status -->
                    <div class="complaint-status-col">
                        <span class="status-badge status-{{ $complaint->status }}">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </div>
                    
                    <!-- Prioritas -->
                    <div class="complaint-priority-col">
                        <span class="priority-badge priority-{{ $complaint->priority }}">
                            <i class="fas fa-{{ $complaint->priority == 'high' ? 'arrow-up' : ($complaint->priority == 'low' ? 'arrow-down' : 'equals') }} me-1" style="font-size: 0.75rem;"></i>
                            {{ ucfirst($complaint->priority) }}
                        </span>
                    </div>
                    
                    <!-- Tanggal -->
                    <div class="complaint-date-col">
                        <p class="complaint-text">{{ $complaint->created_at->format('d/m/Y') }}</p>
                    </div>
                    
                    <!-- Aksi -->
                    <div class="complaint-action-col">
                        <a href="{{ route('citizen.complaints.show', $complaint->id) }}" class="action-btn" title="Lihat Detail">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($complaints->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $complaints->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state-clean">
            <div class="empty-icon-clean">
                <i class="fas fa-box-open"></i>
            </div>
            <h5 class="empty-title-clean">Belum Ada Keluhan</h5>
            <p class="empty-text-clean">Anda belum mengajukan keluhan apapun.</p>
            <a href="{{ route('citizen.complaints.create') }}" class="btn-clean btn-primary-clean">
                <i class="fas fa-plus"></i>
                <span>Ajukan Keluhan Pertama</span>
            </a>
        </div>
    @endif
</div>

<style>
/* Button Clean */
.btn-clean {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    border-radius: 100px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}
.btn-primary-clean {
    background: #b71c1c;
    color: #ffffff;
}
.btn-primary-clean:hover {
    background: #991b1b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(183, 28, 28, 0.2);
    color: white;
}
.btn-outline-clean {
    background: transparent;
    color: #000000;
    border: 2px solid #000000;
}
.btn-outline-clean:hover {
    background: rgba(0,0,0,0.05);
    color: #000000;
}
.page-header-modern .btn-outline-clean {
    background: #ffffff;
    color: #000000;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.page-header-modern .btn-outline-clean:hover {
    background: #f8fafc;
    color: #000000;
    transform: translateY(-2px);
}

/* Empty State Clean */
.empty-state-clean {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    background: transparent;
    border-radius: 24px;
    border: 2px dashed #000000;
}

.empty-icon-clean {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    background: transparent;
    color: #b71c1c;
    border: 2px solid #000000;
    font-size: 1.5rem;
}

.empty-title-clean {
    font-size: 1.25rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 0.5rem;
}

.empty-text-clean {
    color: #4a4a4a;
    margin-bottom: 2rem;
}

/* Dark Mode Overrides */
html.dark .empty-state-clean {
    border-color: #ffffff;
}
html.dark .empty-icon-clean {
    border-color: #ffffff;
}
html.dark .empty-title-clean {
    color: #ffffff;
}
html.dark .empty-text-clean {
    color: #a0aec0;
}

/* Grid Layout */
.complaint-grid-header {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.complaint-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.complaint-item {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.complaint-item:hover {
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
}

.complaint-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1rem;
    align-items: center;
    padding: 1rem;
}

.complaint-title-col { grid-column: span 12; }
.complaint-category-col { grid-column: span 6; }
.complaint-dept-col { grid-column: span 6; }
.complaint-status-col { grid-column: span 4; text-align: center; }
.complaint-priority-col { grid-column: span 4; text-align: center; }
.complaint-date-col { grid-column: span 4; text-align: center; }
.complaint-action-col { grid-column: span 12; text-align: right; }

@media (min-width: 768px) {
    .complaint-title-col { grid-column: span 3; }
    .complaint-category-col { grid-column: span 2; }
    .complaint-dept-col { grid-column: span 2; }
    .complaint-status-col { grid-column: span 1; }
    .complaint-priority-col { grid-column: span 2; }
    .complaint-date-col { grid-column: span 1; }
    .complaint-action-col { grid-column: span 1; }
}

.complaint-title {
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.complaint-desc {
    font-size: 0.875rem;
    color: var(--muted);
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.complaint-text {
    font-size: 0.875rem;
    color: var(--text);
    margin: 0;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
}

.status-submitted, .status-pending {
    background: #dcfce7;
    color: #166534;
}

.status-investigating {
    background: #e0f2fe;
    color: #075985;
}

.status-resolved, .status-closed {
    background: #d1fae5;
    color: #065f46;
}

/* Priority Badges */
.priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 9999px;
}

.priority-medium {
    background: #fef3c7;
    color: #92400e;
}

.priority-high {
    background: #fee2e2;
    color: #991b1b;
}

.priority-low {
    background: #dbeafe;
    color: #1e40af;
}

.priority-urgent {
    background: #fecaca;
    color: #7f1d1d;
}

/* Action Button */
.action-btn {
    padding: 0.5rem;
    color: var(--muted);
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: rgba(245, 158, 11, 0.08);
    color: var(--text);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    font-size: 2rem;
}

.empty-title {
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.5rem;
}

.empty-text {
    color: var(--muted);
    margin-bottom: 1.5rem;
}
</style>
@endsection

