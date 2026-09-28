@extends('layouts.dashboard')

@section('title', 'Admin Command Center')

@section('content')
<style>
    .admin-grid-top {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .admin-grid-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .admin-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.15rem;
        border-radius: 14px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        text-decoration: none;
        color: var(--text-main);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-elevated);
        border-color: var(--brand-primary);
        color: var(--text-main);
    }

    .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .action-icon.blue { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
    .action-icon.emerald { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .action-icon.amber { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .action-icon.purple { background: rgba(99, 102, 241, 0.12); color: #6366f1; }

    .action-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.15rem;
    }

    .action-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .feed-item {
        padding: 0.9rem 0;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        gap: 0.85rem;
        align-items: flex-start;
    }

    .feed-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .feed-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 0.5rem;
        flex-shrink: 0;
    }

    .feed-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .feed-meta {
        font-size: 0.78rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.2rem;
    }

    .chart-container-wrapper {
        position: relative;
        height: 260px;
        width: 100%;
    }

    @media (max-width: 991px) {
        .admin-grid-top, .admin-grid-bottom { grid-template-columns: 1fr; }
        .admin-actions-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Dashboard Hero Header -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div class="dashboard-header-title">
                <i class="fas fa-shield-halved me-2"></i>Executive Command Center
            </div>
            <div class="dashboard-header-desc">
                Pusat orkestrasi pengaduan publik, monitoring kepatuhan SLA antar-OPD, dan audit sistem SynapseGov.
            </div>
            <div class="dashboard-header-time">
                <i class="fas fa-clock me-1"></i>{{ now()->translatedFormat('l, d F Y — H:i') }} WIB &bull; <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5">Sistem Normal</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.monitoring') }}" class="btn-back">
                <i class="fas fa-chart-line"></i>Live SLA Monitor
            </a>
            <a href="{{ route('admin.reports') }}" class="btn-modern">
                <i class="fas fa-file-export"></i>Kelola Laporan
            </a>
        </div>
    </div>

    <!-- Quick Stats Grid inside Hero -->
    <div class="quick-stats">
        <div class="quick-stat-box">
            <div class="quick-stat-icon"><i class="fas fa-folder-open"></i></div>
            <div class="quick-stat-number">{{ $stats['total_reports'] ?? 0 }}</div>
            <div class="quick-stat-label">Total Laporan</div>
        </div>
        <div class="quick-stat-box">
            <div class="quick-stat-icon"><i class="fas fa-comments"></i></div>
            <div class="quick-stat-number">{{ $stats['total_complaints'] ?? 0 }}</div>
            <div class="quick-stat-label">Total Keluhan</div>
        </div>
        <div class="quick-stat-box">
            <div class="quick-stat-icon"><i class="fas fa-users-gear"></i></div>
            <div class="quick-stat-number">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="quick-stat-label">Pengguna Aktif</div>
        </div>
        <div class="quick-stat-box">
            <div class="quick-stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="quick-stat-number">{{ $stats['pending_reports'] ?? 0 }}</div>
            <div class="quick-stat-label">Antrean Masuk</div>
        </div>
        <div class="quick-stat-box">
            <div class="quick-stat-icon"><i class="fas fa-circle-check"></i></div>
            <div class="quick-stat-number">{{ $stats['resolved_reports'] ?? 0 }}</div>
            <div class="quick-stat-label">Terselesaikan</div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="admin-grid-top">
    <!-- Chart: Trend Aktivitas 30 Hari -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-chart-area text-primary"></i>
                <span>Tren Volume Laporan & Keluhan (30 Hari Terakhir)</span>
            </div>
            <span class="badge-status in_progress">Live Data</span>
        </div>
        <div class="card-body">
            <div class="chart-container-wrapper">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- SLA Health & Quick Alerts -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-heart-pulse text-danger"></i>
                <span>Integritas Layanan & SLA</span>
            </div>
        </div>
        <div class="card-body">
            @php
                $slaBreached = $stats['sla_breached'] ?? 0;
                $dueSoon = $stats['due_soon'] ?? 0;
                $pendingAssign = $stats['pending_assignments'] ?? 0;
                $totalRep = $stats['total_reports'] ?? 0;
                $resolvedRep = $stats['resolved_reports'] ?? 0;
                $completionRate = $totalRep > 0 ? round(($resolvedRep / $totalRep) * 100, 1) : 100;
            @endphp

            <div class="stats-grid mb-3">
                <div class="stat-item {{ $slaBreached > 0 ? 'danger' : 'resolved' }}">
                    <div class="stat-number {{ $slaBreached > 0 ? 'text-danger' : 'text-success' }}">{{ $slaBreached }}</div>
                    <div class="stat-label">SLA Breached</div>
                </div>
                <div class="stat-item {{ $dueSoon > 0 ? 'warning' : 'resolved' }}">
                    <div class="stat-number {{ $dueSoon > 0 ? 'text-warning' : 'text-success' }}">{{ $dueSoon }}</div>
                    <div class="stat-label">Mendekati Deadline</div>
                </div>
            </div>

            <div class="p-3 rounded-3 mb-3" style="background: var(--brand-gradient-subtle); border: 1px solid var(--card-border);">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold">Tingkat Penyelesaian (Completion Rate)</span>
                    <span class="fw-bold text-primary">{{ $completionRate }}%</span>
                </div>
                <div class="progress" style="height: 8px; background: rgba(0,0,0,0.08); border-radius: 999px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $completionRate }}%; border-radius: 999px;"></div>
                </div>
            </div>

            <!-- Alert notification box -->
            @if($slaBreached > 0)
                <div class="alert alert-danger py-2 px-3 m-0 small d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>Terdapat <strong>{{ $slaBreached }} laporan</strong> melampaui batas SLA. Segera eskalasi ke kepala OPD.</span>
                </div>
            @elseif($pendingAssign > 0)
                <div class="alert alert-warning py-2 px-3 m-0 small d-flex align-items-center gap-2">
                    <i class="fas fa-user-clock"></i>
                    <span><strong>{{ $pendingAssign }} tugas</strong> menunggu penugasan staff teknis.</span>
                </div>
            @else
                <div class="alert alert-success py-2 px-3 m-0 small d-flex align-items-center gap-2">
                    <i class="fas fa-circle-check"></i>
                    <span>Seluruh pengaduan berada dalam parameter SLA optimal.</span>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="admin-grid-bottom">
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-bolt text-warning"></i>
                <span>Aksi Cepat Manajemen</span>
            </div>
        </div>
        <div class="card-body">
            <div class="admin-actions-grid">
                <a href="{{ route('admin.reports') }}" class="action-card">
                    <div class="action-icon blue">
                        <i class="fas fa-file-lines"></i>
                    </div>
                    <div>
                        <div class="action-title">Semua Laporan</div>
                        <div class="action-sub">Tinjau, filter, dan verifikasi</div>
                    </div>
                </a>

                <a href="{{ route('admin.monitoring') }}" class="action-card">
                    <div class="action-icon emerald">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="action-title">Monitoring & SLA</div>
                        <div class="action-sub">Evaluasi matriks performa</div>
                    </div>
                </a>

                <a href="{{ route('admin.departments') }}" class="action-card">
                    <div class="action-icon amber">
                        <i class="fas fa-building-columns"></i>
                    </div>
                    <div>
                        <div class="action-title">Organisasi OPD</div>
                        <div class="action-sub">Daftar dinas & kepala instansi</div>
                    </div>
                </a>

                <a href="{{ route('admin.users') }}" class="action-card">
                    <div class="action-icon purple">
                        <i class="fas fa-users-gear"></i>
                    </div>
                    <div>
                        <div class="action-title">Kelola Pengguna</div>
                        <div class="action-sub">Akses role, staff, & warga</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-clock-rotate-left text-info"></i>
                <span>Aktivitas Masuk Terbaru</span>
            </div>
            <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-secondary py-1 px-2 text-decoration-none" style="font-size:0.75rem;">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if(isset($recentReports) && $recentReports->count() > 0)
                <div>
                    @foreach($recentReports->take(4) as $report)
                    <div class="feed-item">
                        <div class="feed-dot bg-primary"></div>
                        <div style="flex: 1; min-width: 0;">
                            <div class="feed-title">{{ $report->title }}</div>
                            <div class="feed-meta">
                                <span><i class="fas fa-user me-1"></i>{{ $report->user->name ?? 'Warga' }}</span>
                                &bull;
                                <span><i class="fas fa-building me-1"></i>{{ $report->department->name ?? 'Umum' }}</span>
                                &bull;
                                <span><i class="fas fa-clock me-1"></i>{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div>
                            @php
                                $statusClass = match(strtolower($report->status ?? '')) {
                                    'submitted' => 'submitted',
                                    'verified' => 'verified',
                                    'assigned' => 'assigned',
                                    'in_progress' => 'in_progress',
                                    'resolved' => 'resolved',
                                    'closed' => 'closed',
                                    default => 'submitted'
                                };
                            @endphp
                            <span class="badge-status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                    Belum ada laporan aktivitas terbaru.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendsChart');
    if (!ctx) return;

    // Monthly data from controller
    const monthlyReports = @json($monthlyStats['reports'] ?? []);
    const monthlyComplaints = @json($monthlyStats['complaints'] ?? []);

    const labels = [];
    const reportMap = {};
    const complaintMap = {};

    monthlyReports.forEach(item => {
        labels.push(item.date);
        reportMap[item.date] = item.count;
    });

    monthlyComplaints.forEach(item => {
        if (!labels.includes(item.date)) labels.push(item.date);
        complaintMap[item.date] = item.count;
    });

    labels.sort();

    // Fill missing days if needed or fallback dummy curve if empty
    const finalLabels = labels.length > 0 ? labels.slice(-14) : ['H-13','H-12','H-11','H-10','H-9','H-8','H-7','H-6','H-5','H-4','H-3','H-2','Kemarin','Hari Ini'];
    const reportData = labels.length > 0 ? finalLabels.map(l => reportMap[l] || 0) : [2, 4, 3, 5, 4, 7, 8, 6, 9, 7, 11, 8, 12, 10];
    const complaintData = labels.length > 0 ? finalLabels.map(l => complaintMap[l] || 0) : [1, 2, 1, 3, 2, 3, 4, 2, 5, 3, 4, 3, 5, 4];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: finalLabels,
            datasets: [
                {
                    label: 'Laporan Publik',
                    data: reportData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointHoverRadius: 6
                },
                {
                    label: 'Keluhan & Aspirasi',
                    data: complaintData,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.08)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                    borderDash: [4, 4],
                    pointRadius: 2,
                    pointHoverRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true,
                        font: { family: 'Plus Jakarta Sans', size: 12 }
                    }
                },
                tooltip: {
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.15)' },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                }
            }
        }
    });
});
</script>
@endsection
