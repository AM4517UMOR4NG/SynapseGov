@extends('layouts.dashboard')

@section('title', 'Citizen Dashboard')

@section('content')
<style>
    /* Ultra-Minimalist Citizen Dashboard */
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
        color: var(--text-main);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Minimalist Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.85rem 1.5rem;
        background: var(--brand-primary);
        color: #ffffff !important;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: transform 0.2s ease, background 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: center;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        background: var(--brand-primary-hover, #2563eb);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
    }

    .action-btn.outline {
        background: transparent;
        color: var(--text-main) !important;
        border: 1px solid var(--card-border);
    }

    .action-btn.outline:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: var(--text-muted);
    }

    /* Clean Info Items */
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

    .info-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-main);
        margin-bottom: 0.2rem;
    }
    
    .info-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .status-pill {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        border: 1px solid var(--card-border);
        background: rgba(255, 255, 255, 0.03);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .empty-state {
        padding: 2rem 0;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .empty-state a {
        color: var(--text-main);
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; letter-spacing: -0.04em; margin-bottom: 0.5rem;">Dashboard Warga</h1>
        <p style="color: var(--text-muted); font-size: 1rem;">Pantau dan sampaikan aspirasi Anda untuk lingkungan yang lebih baik.</p>
    </div>

    <!-- Quick Stats -->
    <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); margin-bottom: 2rem;">
        <div class="card" style="padding: 1.75rem; border-radius: 16px;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-file-alt text-primary"></i> Total Laporan</div>
            <div style="font-size: 2.8rem; font-weight: 800; line-height: 1; color: var(--text-main);">{{ $myReports->count() ?? 0 }}</div>
        </div>
        <div class="card" style="padding: 1.75rem; border-radius: 16px;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-exclamation-triangle text-warning"></i> Total Keluhan</div>
            <div style="font-size: 2.8rem; font-weight: 800; line-height: 1; color: var(--text-main);">{{ $myComplaints->count() ?? 0 }}</div>
        </div>
        <div class="card" style="padding: 1.75rem; border-radius: 16px;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-clock text-info"></i> Diproses</div>
            <div style="font-size: 2.8rem; font-weight: 800; line-height: 1; color: var(--text-main);">{{ $pendingCount ?? 0 }}</div>
        </div>
        <div class="card" style="padding: 1.75rem; border-radius: 16px;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-check-circle text-success"></i> Selesai</div>
            <div style="font-size: 2.8rem; font-weight: 800; line-height: 1; color: var(--text-main);">{{ $resolvedCount ?? 0 }}</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Actions -->
        <div class="card" style="border-radius: 16px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="padding: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Mulai Aspirasi</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Sampaikan laporan infrastruktur, pelayanan publik, atau keluhan lingkungan di sekitar Anda.</p>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="{{ route('citizen.reports.create') }}" class="action-btn">
                        Buat Laporan Baru
                    </a>
                    <a href="{{ route('citizen.complaints.create') }}" class="action-btn outline">
                        Buat Keluhan
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="card" style="border-radius: 16px;">
            <div style="padding: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Laporan Terkini</h3>
                    <a href="{{ route('citizen.reports.index') }}" style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-decoration: none;">Lihat Semua</a>
                </div>
                
                @if($myReports && $myReports->count() > 0)
                    <ul class="info-list">
                        @foreach($myReports->take(4) as $report)
                            <li class="info-item">
                                <div>
                                    <div class="info-title">{{ Str::limit($report->title, 40) }}</div>
                                    <div class="info-desc">{{ $report->department->name ?? 'Belum ditentukan' }} • {{ $report->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="status-pill">{{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        Belum ada laporan.<br><a href="{{ route('citizen.reports.create') }}">Buat laporan pertama Anda</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="card" style="border-radius: 16px;">
            <div style="padding: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Keluhan Terkini</h3>
                    <a href="{{ route('citizen.complaints.index') }}" style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-decoration: none;">Lihat Semua</a>
                </div>
                
                @if($myComplaints && $myComplaints->count() > 0)
                    <ul class="info-list">
                        @foreach($myComplaints->take(4) as $complaint)
                            <li class="info-item">
                                <div>
                                    <div class="info-title">{{ Str::limit($complaint->title, 40) }}</div>
                                    <div class="info-desc">{{ $complaint->department->name ?? 'Belum ditentukan' }} • {{ $complaint->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="status-pill">{{ ucfirst($complaint->status) }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        Belum ada keluhan.<br><a href="{{ route('citizen.complaints.create') }}">Sampaikan keluhan Anda</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
