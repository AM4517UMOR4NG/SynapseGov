@extends('layouts.dashboard')

@section('title', 'Dashboard Warga')

@section('content')
<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
    }
    
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .status-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        background: rgba(183, 28, 28, 0.05);
        border: 1px solid rgba(183, 28, 28, 0.1);
        color: var(--brand-primary);
    }
</style>

<div class="dashboard-container">
    
    <!-- Page Header (Identical to Create Pages) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-modern d-flex justify-content-between align-items-center">
                <div>
                    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
                    <p>Pantau laporan dan sampaikan aspirasi Anda untuk lingkungan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-2">Total Laporan</div>
                            <h2 class="fw-bold mb-0">{{ $myReports->count() ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-2">Total Keluhan</div>
                            <h2 class="fw-bold mb-0">{{ $myComplaints->count() ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-2">Sedang Diproses</div>
                            <h2 class="fw-bold mb-0">{{ $pendingCount ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon-wrapper bg-info-subtle text-info">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 stat-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase mb-2">Telah Selesai</div>
                            <h2 class="fw-bold mb-0">{{ $resolvedCount ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon-wrapper bg-success-subtle text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Actions (Left) -->
        <div class="col-lg-4 d-flex flex-column">
            <div class="card shadow-sm border-0 flex-grow-1">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center border-bottom pb-3 mb-4">
                        <h5 class="fw-bold m-0" style="color: var(--brand-primary) !important;">
                            <i class="fas fa-bolt me-2"></i>Mulai Aspirasi
                        </h5>
                    </div>
                    <p class="text-muted mb-4 pb-2" style="font-size: 0.95rem; line-height: 1.6;">
                        Ada masalah infrastruktur atau ketidaknyamanan dalam pelayanan publik? Laporkan segera ke instansi terkait.
                    </p>
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary py-3 fw-bold shadow-sm" style="border-radius: 12px;">
                            Buat Laporan Baru
                        </a>
                        <a href="{{ route('citizen.complaints.create') }}" class="btn btn-outline-secondary py-3 fw-bold" style="border-radius: 12px; border-width: 2px;">
                            Ajukan Keluhan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities (Right) -->
        <div class="col-lg-8 d-flex flex-column">
            <div class="row g-4 flex-grow-1">
                <!-- Recent Reports -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="card shadow-sm border-0 flex-grow-1">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <h5 class="fw-bold m-0" style="color: var(--brand-primary) !important;">
                                    <i class="fas fa-file-alt me-2"></i>Laporan Terkini
                                </h5>
                                <a href="{{ route('citizen.reports.index') }}" class="text-decoration-none small fw-bold text-muted">Lihat Semua</a>
                            </div>
                            
                            @if($myReports && $myReports->count() > 0)
                                <ul class="info-list">
                                    @foreach($myReports->take(4) as $report)
                                        <li class="info-item flex-column flex-sm-row gap-2">
                                            <div>
                                                <div class="fw-bold mb-1" style="font-size: 0.95rem; color: var(--text-main);">{{ Str::limit($report->title, 40) }}</div>
                                                <div class="text-muted small">{{ $report->department->name ?? 'Belum ditentukan' }} &bull; {{ $report->created_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="status-pill align-self-start">{{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fs-1 mb-3 opacity-25"></i>
                                    <p class="mb-0 small">Belum ada laporan.<br><a href="{{ route('citizen.reports.create') }}" class="fw-bold">Buat sekarang</a></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Complaints -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="card shadow-sm border-0 flex-grow-1">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <h5 class="fw-bold m-0" style="color: var(--brand-primary) !important;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Keluhan Terkini
                                </h5>
                                <a href="{{ route('citizen.complaints.index') }}" class="text-decoration-none small fw-bold text-muted">Lihat Semua</a>
                            </div>
                            
                            @if($myComplaints && $myComplaints->count() > 0)
                                <ul class="info-list">
                                    @foreach($myComplaints->take(4) as $complaint)
                                        <li class="info-item flex-column flex-sm-row gap-2">
                                            <div>
                                                <div class="fw-bold mb-1" style="font-size: 0.95rem; color: var(--text-main);">{{ Str::limit($complaint->title, 40) }}</div>
                                                <div class="text-muted small">{{ $complaint->department->name ?? 'Belum ditentukan' }} &bull; {{ $complaint->created_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="status-pill align-self-start">{{ ucfirst($complaint->status) }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-1 mb-3 opacity-25"></i>
                                    <p class="mb-0 small">Belum ada keluhan.<br><a href="{{ route('citizen.complaints.create') }}" class="fw-bold">Sampaikan sekarang</a></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
