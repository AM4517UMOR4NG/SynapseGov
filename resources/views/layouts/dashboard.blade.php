<!DOCTYPE html>
@php
    $appLang = auth()->check() ? (auth()->user()->getSettings('language', 'id') ?? 'id') : 'id';
    $isDark = auth()->check() ? ((auth()->user()->getSettings('theme', 'light') ?? 'light') === 'dark') : false;
    $t = function(string $key) use ($appLang) {
        $id = [
            'brand' => 'SynapseGov',
            'navigation' => 'Navigasi Menu',
            'theme' => 'Tema Tampilan',
            'view_profile' => 'Profil Saya',
            'edit_profile' => 'Ubah Profil',
            'settings' => 'Pengaturan Akun',
            'logout' => 'Keluar Sistem',
            'dashboard' => 'Dashboard',
            'reports' => 'Laporan Publik',
            'complaints' => 'Keluhan & Aspirasi',
            'users' => 'Kelola Pengguna',
            'departments' => 'Organisasi Perangkat Daerah',
            'monitoring' => 'Monitoring & SLA',
            'citizen_reports' => 'Laporan Saya',
            'citizen_complaints' => 'Keluhan Saya',
        ];
        $en = [
            'brand' => 'SynapseGov',
            'navigation' => 'Navigation',
            'theme' => 'Display Theme',
            'view_profile' => 'My Profile',
            'edit_profile' => 'Edit Profile',
            'settings' => 'Account Settings',
            'logout' => 'Log Out',
            'dashboard' => 'Dashboard',
            'reports' => 'Public Reports',
            'complaints' => 'Complaints & Grievances',
            'users' => 'User Management',
            'departments' => 'Departments (OPD)',
            'monitoring' => 'Monitoring & SLA',
            'citizen_reports' => 'My Reports',
            'citizen_complaints' => 'My Complaints',
        ];
        return $appLang === 'en' ? ($en[$key] ?? $key) : ($id[$key] ?? $key);
    };
@endphp
<html lang="{{ $appLang }}" class="{{ $isDark ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'SynapseGov') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SynapseGov Next-Gen Design System -->
    <style>
        :root {
            /* Brand Identity Colors */
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-secondary: #0ea5e9;
            --brand-accent: #6366f1;
            --brand-gradient: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
            --brand-gradient-hover: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);
            --brand-gradient-subtle: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(14, 165, 233, 0.08) 100%);
            --brand-glow: 0 4px 20px rgba(37, 99, 235, 0.35);

            /* Semantic Status Colors */
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.12);
            --success-border: rgba(16, 185, 129, 0.3);
            --warning: #f59e0b;
            --warning-bg: rgba(245, 158, 11, 0.12);
            --warning-border: rgba(245, 158, 11, 0.3);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.12);
            --danger-border: rgba(239, 68, 68, 0.3);
            --info: #06b6d4;
            --info-bg: rgba(6, 182, 212, 0.12);
            --info-border: rgba(6, 182, 212, 0.3);

            /* Light Canvas Variables */
            --bg-canvas: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.94);
            --card-border: rgba(226, 232, 240, 0.85);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-subtle: #94a3b8;
            --shadow-ambient: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --shadow-elevated: 0 12px 32px -4px rgba(37, 99, 235, 0.1), 0 4px 12px -2px rgba(15, 23, 42, 0.05);
            --navbar-bg: rgba(255, 255, 255, 0.88);
            --sidebar-bg: #ffffff;
            --table-hover: rgba(37, 99, 235, 0.03);
            --input-bg: #ffffff;
            --input-border: #e2e8f0;

            /* Legacy compatibility mapping */
            --primary-color: var(--brand-primary);
            --secondary-color: #334155;
            --accent-color: var(--brand-secondary);
            --success-color: var(--success);
            --warning-color: var(--warning);
            --danger-color: var(--danger);
            --info-color: var(--info);
            --light-bg: var(--brand-gradient-subtle);
            --text-primary: var(--text-main);
            --text-secondary: var(--text-muted);
            --border-color: var(--card-border);
            --shadow-light: var(--shadow-ambient);
            --shadow-medium: var(--shadow-ambient);
            --shadow-heavy: var(--shadow-elevated);
            --bg: var(--bg-canvas);
            --card: var(--card-bg);
            --text: var(--text-main);
            --muted: var(--text-muted);
            --primary: var(--brand-primary);
            --primary-600: var(--brand-primary-hover);
            --secondary: var(--brand-secondary);
            --border: var(--card-border);
        }

        .dark {
            /* Dark Canvas Variables (Modern Slate) */
            --bg-canvas: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.75);
            --card-border: rgba(255, 255, 255, 0.12);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --text-subtle: #64748b;
            --shadow-ambient: 0 4px 20px -2px rgba(0, 0, 0, 0.4);
            --shadow-elevated: 0 10px 30px -4px rgba(0, 0, 0, 0.5), 0 0 15px rgba(59, 130, 246, 0.1);
            --navbar-bg: rgba(15, 23, 42, 0.88);
            --sidebar-bg: #0f172a;
            --table-hover: rgba(59, 130, 246, 0.08);
            --input-bg: #1e293b;
            --input-border: #334155;

            /* Legacy compatibility mapping */
            --primary-color: #3b82f6;
            --secondary-color: #64748b;
            --accent-color: #38bdf8;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --bg: var(--bg-canvas);
            --card: var(--card-bg);
            --text: var(--text-main);
            --muted: var(--text-muted);
            --border: var(--card-border);
        }

        /* Base Typography & Canvas */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Inter', sans-serif;
            background-color: var(--bg-canvas);
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.04) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.04) 0px, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-main);
            padding-top: 64px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .dark body {
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(99, 102, 241, 0.06) 0px, transparent 50%);
        }

        /* Top Navbar */
        .navbar {
            background: var(--navbar-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: none !important;
            box-shadow: none !important;
            padding: 0.65rem 1.25rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            transition: all 0.3s ease;
        }

        /* Custom Dropdown Styling */
        .dropdown-menu {
            background: var(--card-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border) !important;
            border-radius: 14px;
            box-shadow: var(--shadow-elevated) !important;
            padding: 0.5rem;
            min-width: 240px;
            margin-top: 0.75rem !important;
            z-index: 1050;
        }

        .dropdown-header {
            padding: 0.5rem 1rem;
            color: var(--text-main);
        }

        .dropdown-item {
            color: var(--text-main) !important;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            margin-bottom: 0.15rem;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background: rgba(37, 99, 235, 0.08) !important;
            color: var(--brand-primary) !important;
        }

        .dropdown-divider {
            border-top: 1px solid var(--card-border);
            margin: 0.5rem 0;
            opacity: 1;
        }

        .navbar-brand, 
        .navbar-brand:hover, 
        .navbar-brand:focus,
        .navbar-brand:active,
        .navbar-brand:visited,
        .navbar-brand *,
        .navbar-brand:hover * {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none !important;
            text-decoration-line: none !important;
            border-bottom: none !important;
            color: var(--text-main) !important;
            padding: 0;
        }

        .brand-logo-icon,
        .brand-logo-text,
        .brand-name,
        .brand-glow,
        .brand-live-badge,
        .brand-live-badge * {
            text-decoration: none !important;
            text-decoration-line: none !important;
            border-bottom: none !important;
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            background: var(--brand-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
            transition: transform 0.25s ease;
        }

        .navbar-brand:hover .brand-logo-icon {
            transform: scale(1.05) rotate(-3deg);
        }

        .brand-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .brand-glow {
            background: var(--brand-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-live-badge {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .live-dot {
            width: 6px;
            height: 6px;
            background-color: var(--success);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px var(--success);
            animation: pulse-dot 2s infinite ease-in-out;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
        }

        /* Topbar Controls */
        .btn-theme-toggle {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-theme-toggle:hover {
            color: var(--brand-primary);
            border-color: var(--brand-primary);
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-1px);
        }

        /* User Profile Pill in Navbar */
        .user-profile-pill {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 0.35rem 0.85rem 0.35rem 0.4rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-main) !important;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .user-profile-pill:hover {
            border-color: var(--brand-primary);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
            transform: translateY(-1px);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border) !important;
            border-radius: 14px !important;
            box-shadow: var(--shadow-elevated) !important;
            padding: 0.5rem;
            min-width: 230px;
            margin-top: 0.6rem;
            z-index: 9999 !important;
        }

        .dropdown-header {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.75rem;
        }

        .dropdown-item {
            color: var(--text-main);
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(37, 99, 235, 0.08);
            color: var(--brand-primary);
            transform: translateX(2px);
        }

        .dropdown-item.text-danger:hover {
            background: var(--danger-bg);
            color: var(--danger) !important;
        }

        /* Sidebar & Layout */
        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: calc(100vh - 64px);
            background: var(--bg-canvas);
        }

        @media (min-width: 992px) {
            .layout { grid-template-columns: auto 1fr; }
            .sidebar { 
                width: 72px; 
                overflow-x: hidden; 
                transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar:hover { 
                width: 260px; 
            }
            .sidebar:not(:hover) .side-link span,
            .sidebar:not(:hover) .sidebar-bottom-actions span { display: none; }
            .sidebar:not(:hover) .side-link { justify-content: center; padding: 0.7rem 0; }
            .sidebar:not(:hover) .section-title { opacity: 0; }
            .sidebar:not(:hover) .sidebar-bottom-actions .btn { padding: 0.5rem; justify-content: center; }
            .sidebar:not(:hover) .sidebar-bottom-actions .btn i { margin: 0 !important; }
        }

        .sidebar {
            background: var(--sidebar-bg);
            border-right: none !important;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            z-index: 1020;
            box-shadow: none !important;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar .section-title {
            padding: 0.75rem 1.25rem 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-subtle);
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            margin: 0.2rem 0.6rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .side-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .side-link:hover {
            color: var(--brand-primary);
            background: rgba(37, 99, 235, 0.06);
            transform: translateX(3px);
        }

        .side-link:hover i {
            transform: scale(1.15);
        }

        .side-link.active {
            background: var(--brand-gradient);
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .side-link.active i {
            color: #ffffff !important;
        }

        /* Main Content Stage */
        .content {
            padding: 2rem 2.25rem;
            max-width: 1600px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .content { padding: 1.25rem; }
        }

        /* Cards & Glassmorphism */
        .card {
            background: var(--card-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border) !important;
            border-radius: 16px !important;
            box-shadow: var(--shadow-ambient) !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: var(--shadow-elevated) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--card-border) !important;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-body {
            padding: 1.5rem;
            color: var(--text-main);
        }

        /* Buttons & Interactions */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.6rem 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--brand-gradient) !important;
            border: none;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            background: var(--brand-gradient-hover) !important;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .btn-outline-secondary {
            background: var(--card-bg);
            border-color: var(--card-border);
            color: var(--text-muted);
        }

        .btn-outline-secondary:hover {
            background: rgba(37, 99, 235, 0.05);
            border-color: var(--brand-primary);
            color: var(--brand-primary);
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            border: 1.5px solid var(--input-border) !important;
            color: var(--text-main) !important;
            border-radius: 10px;
            padding: 0.65rem 0.95rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18) !important;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-main);
            margin-bottom: 0.4rem;
        }

        /* Modern Status Pills */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.8rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.78rem;
            letter-spacing: 0.02em;
            text-transform: capitalize;
        }

        .badge-status::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-status.submitted {
            background: var(--warning-bg);
            color: #d97706;
            border: 1px solid var(--warning-border);
        }
        .badge-status.submitted::before { background: #f59e0b; }

        .badge-status.verified {
            background: var(--info-bg);
            color: #0284c7;
            border: 1px solid var(--info-border);
        }
        .badge-status.verified::before { background: #0ea5e9; }

        .badge-status.assigned {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
        .badge-status.assigned::before { background: #6366f1; }

        .badge-status.in_progress {
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, 0.3);
        }
        .badge-status.in_progress::before {
            background: #2563eb;
            animation: pulse-dot 1.5s infinite;
        }

        .badge-status.resolved {
            background: var(--success-bg);
            color: #059669;
            border: 1px solid var(--success-border);
        }
        .badge-status.resolved::before { background: #10b981; }

        .badge-status.closed {
            background: rgba(100, 116, 139, 0.12);
            color: #64748b;
            border: 1px solid rgba(100, 116, 139, 0.3);
        }
        .badge-status.closed::before { background: #64748b; }

        .badge-status.rejected, .badge-status.sla_breached, .badge-status.urgent {
            background: var(--danger-bg);
            color: #dc2626;
            border: 1px solid var(--danger-border);
        }
        .badge-status.rejected::before, .badge-status.sla_breached::before, .badge-status.urgent::before {
            background: #ef4444;
            animation: pulse-dot 1s infinite;
        }

        /* Modern Tables */
        .table {
            color: var(--text-main) !important;
            border-color: var(--card-border) !important;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            background: var(--brand-gradient-subtle) !important;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.9rem 1rem;
            border-bottom: 1.5px solid var(--card-border) !important;
            border-top: none;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--card-border);
            background: transparent !important;
            color: var(--text-main);
            font-size: 0.88rem;
        }

        .table tbody tr:hover td {
            background: var(--table-hover) !important;
        }

        /* Stat Card Widgets */
        .stats-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-ambient);
            transition: all 0.25s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-elevated);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--brand-gradient);
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-main);
            margin-bottom: 0.2rem;
            line-height: 1.1;
        }

        .stats-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stats-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--brand-gradient-subtle);
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Avatars */
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.4);
            flex-shrink: 0;
        }

        .avatar-fallback {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #ffffff;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        /* Floating Alert Banners */
        .alert {
            border-radius: 14px;
            border: 1px solid transparent;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-ambient);
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: var(--success-bg) !important;
            color: #065f46 !important;
            border-color: var(--success-border) !important;
        }
        .dark .alert-success { color: #34d399 !important; }

        .alert-danger {
            background: var(--danger-bg) !important;
            color: #991b1b !important;
            border-color: var(--danger-border) !important;
        }
        .dark .alert-danger { color: #f87171 !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.4);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.7);
        }

        /* SynapseGov Global Dashboard Components */
        .page-header-modern, .dashboard-header, .dept-header, .reports-header, .users-header, .complaints-header, .departments-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0284c7 100%) !important;
            color: #ffffff !important;
            padding: 2.25rem 2rem !important;
            border-radius: 16px !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 10px 30px -8px rgba(37, 99, 235, 0.45) !important;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .dark .page-header-modern, .dark .dashboard-header, .dark .dept-header, .dark .reports-header, .dark .users-header, .dark .complaints-header, .dark .departments-header {
            background: linear-gradient(135deg, #090e1a 0%, #172554 50%, #1e3a8a 100%) !important;
            box-shadow: 0 10px 30px -8px rgba(0, 0, 0, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .dashboard-header-title, .page-header-modern h1, .page-header-modern h2, .dept-header h1, .reports-header h1, .users-header h1, .complaints-header h1, .departments-header h1 {
            color: #ffffff !important;
            font-size: 1.85rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem !important;
        }

        .dashboard-header-desc, .page-header-modern p, .dept-header p, .reports-header p, .users-header p, .complaints-header p, .departments-header p {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 0.95rem !important;
            margin-bottom: 0 !important;
        }

        .dashboard-header-time {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.85rem !important;
            margin-top: 0.75rem !important;
        }

        /* Modern Quick Stat Box inside Hero */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .quick-stat-box {
            background: rgba(255, 255, 255, 0.12) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 1.1rem 1rem !important;
            border-radius: 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            text-align: center;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            color: #ffffff !important;
        }

        .quick-stat-box:hover {
            background: rgba(255, 255, 255, 0.22) !important;
            transform: translateY(-3px) !important;
            border-color: rgba(255, 255, 255, 0.45) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
        }

        .quick-stat-icon {
            font-size: 1.4rem;
            margin-bottom: 0.4rem;
            opacity: 0.95;
        }

        .quick-stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0.2rem;
            letter-spacing: -0.02em;
        }

        .quick-stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            opacity: 0.85;
        }

        /* Modern Unified Cards */
        .dashboard-card, .card-modern, .reports-card, .users-card, .complaints-card, .departments-card {
            background: var(--card-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px !important;
            border: 1px solid var(--card-border) !important;
            box-shadow: var(--shadow-ambient) !important;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            margin-bottom: 1.5rem;
        }

        .dashboard-card:hover, .card-modern:hover, .reports-card:hover, .users-card:hover, .complaints-card:hover, .departments-card:hover {
            box-shadow: var(--shadow-elevated) !important;
            transform: translateY(-2px);
        }

        .dashboard-card-header, .card-header-modern, .reports-card-header, .users-card-header, .complaints-card-header, .departments-card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--card-border) !important;
            padding: 1.25rem 1.5rem !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-main) !important;
        }

        .dashboard-card-header h3, .card-header-modern h6, .card-header-modern h5, .reports-card-header h3, .users-card-header h3, .complaints-card-header h3, .departments-card-header h3 {
            margin: 0 !important;
            color: var(--text-main) !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            letter-spacing: -0.01em;
        }

        .dashboard-card-header i, .card-header-modern i, .reports-card-header i, .users-card-header i, .complaints-card-header i, .departments-card-header i {
            color: var(--brand-primary) !important;
            font-size: 1.15rem;
        }

        .dashboard-card-body, .reports-card-body, .users-card-body, .complaints-card-body, .departments-card-body {
            padding: 1.5rem !important;
            color: var(--text-main) !important;
        }

        /* Modern Stat Grid & Stat Items */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-item {
            padding: 1.2rem !important;
            background: var(--card-bg) !important;
            border-radius: 12px !important;
            border: 1px solid var(--card-border) !important;
            border-left: 4px solid var(--brand-primary) !important;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
        }

        .stat-item.pending {
            border-left-color: #0284c7 !important;
            background: rgba(2, 132, 199, 0.08) !important;
        }

        .stat-item.resolved {
            border-left-color: #10b981 !important;
            background: rgba(16, 185, 129, 0.08) !important;
        }

        .stat-item.warning {
            border-left-color: #f59e0b !important;
            background: rgba(245, 158, 11, 0.08) !important;
        }

        .stat-item.danger {
            border-left-color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.08) !important;
        }

        .stat-number {
            font-size: 1.9rem !important;
            font-weight: 800 !important;
            color: var(--text-main) !important;
            line-height: 1.1;
            margin-bottom: 0.35rem !important;
        }

        .stat-label {
            font-size: 0.8rem !important;
            font-weight: 600;
            color: var(--text-muted) !important;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Modern Buttons */
        .btn-back, .btn-modern {
            background: rgba(255, 255, 255, 0.16) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #ffffff !important;
            padding: 0.6rem 1.25rem !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            font-size: 0.88rem !important;
            border: 1px solid rgba(255, 255, 255, 0.28) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .btn-back:hover, .btn-modern:hover {
            background: rgba(255, 255, 255, 0.28) !important;
            color: #ffffff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2) !important;
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">


                <!-- Mobile Hamburger Button -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#topbarNav">
                    <i class="fas fa-bars text-secondary"></i>
                </button>

                <div class="collapse navbar-collapse" id="topbarNav">
                    <ul class="navbar-nav me-auto">
                        <!-- Navigation is centered in the side rail -->
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <!-- Theme Toggle Button -->
                        <li class="nav-item">
                            <button class="btn btn-theme-toggle" id="themeToggle" type="button" title="{{ $t('theme') }}">
                                <i class="fas fa-moon" id="themeIcon"></i>
                                <span class="d-none d-md-inline" id="themeText">{{ $isDark ? 'Dark' : 'Light' }}</span>
                            </button>
                        </li>

                        @auth
                            <!-- User Account Dropdown -->
                            <li class="nav-item dropdown">
                                @php
                                    $initials = auth()->user()->getAvatarInitials();
                                    $avatarColor = auth()->user()->getAvatarColor();
                                    $avatarPath = auth()->user()->avatar;
                                    $avatarUrl = $avatarPath ? route('avatar.show', basename($avatarPath)) : null;
                                @endphp
                                <a class="user-profile-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="avatar me-2"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                                        <span class="avatar-fallback me-2" style="display: none; background-color: {{ $avatarColor }};">{{ $initials }}</span>
                                    @else
                                        <span class="avatar-fallback me-2" style="background-color: {{ $avatarColor }};">{{ $initials }}</span>
                                    @endif
                                    <span class="d-none d-md-inline me-1">{{ auth()->user()->name }}</span>
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 ms-1 text-uppercase" style="font-size:0.7rem;">{{ auth()->user()->role }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-header">
                                        <div class="fw-bold text-truncate" style="color: var(--text-main);">{{ auth()->user()->name }}</div>
                                        <div class="text-muted small text-truncate">{{ auth()->user()->email }}</div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-id-badge text-primary"></i>{{ $t('view_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-pen text-info"></i>{{ $t('edit_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="fas fa-sliders text-secondary"></i>{{ $t('settings') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-arrow-right-from-bracket"></i>{{ $t('logout') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm px-3" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar + Content Stage Layout -->
        <div class="layout">
            <!-- Sleek Modern Sidebar -->
            <aside class="sidebar" id="sidebar">
                <div class="section-title">{{ $t('navigation') }}</div>
                <nav class="mb-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}"><i class="fas fa-file-shield"></i><span>{{ $t('reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}" href="{{ route('admin.complaints') }}"><i class="fas fa-triangle-exclamation"></i><span>{{ $t('complaints') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}"><i class="fas fa-users-gear"></i><span>{{ $t('users') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}" href="{{ route('admin.departments') }}"><i class="fas fa-sitemap"></i><span>{{ $t('departments') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.monitoring*') ? 'active' : '' }}" href="{{ route('admin.monitoring') }}"><i class="fas fa-clock-rotate-left"></i><span>{{ $t('monitoring') }}</span></a>
                        @elseif(auth()->user()->isDepartmentHead() || auth()->user()->isStaff())
                            <a class="side-link {{ request()->routeIs('administration.dashboard') ? 'active' : '' }}" href="{{ route('administration.dashboard') }}"><i class="fas fa-chart-pie"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('administration.reports*') ? 'active' : '' }}" href="{{ route('administration.reports') }}"><i class="fas fa-file-shield"></i><span>{{ $t('reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('administration.complaints*') ? 'active' : '' }}" href="{{ route('administration.complaints') }}"><i class="fas fa-triangle-exclamation"></i><span>{{ $t('complaints') }}</span></a>
                            @if(auth()->user()->isDepartmentHead())
                                <a class="side-link {{ request()->routeIs('administration.staff*') ? 'active' : '' }}" href="{{ route('administration.staff') }}"><i class="fas fa-user-tie"></i><span>Staff Lapangan</span></a>
                            @endif
                        @else
                            <a class="side-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}" href="{{ route('citizen.dashboard') }}"><i class="fas fa-chart-pie"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}" href="{{ route('citizen.reports.create') }}"><i class="fas fa-circle-plus"></i><span>{{ $appLang==='en' ? 'Create Report' : 'Buat Laporan' }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.reports*') && !request()->routeIs('citizen.reports.create') ? 'active' : '' }}" href="{{ route('citizen.reports.index') }}"><i class="fas fa-file-lines"></i><span>{{ $t('citizen_reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.complaints.create') ? 'active' : '' }}" href="{{ route('citizen.complaints.create') }}"><i class="fas fa-circle-plus"></i><span>{{ $appLang==='en' ? 'Create Complaint' : 'Buat Keluhan' }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.complaints*') && !request()->routeIs('citizen.complaints.create') ? 'active' : '' }}" href="{{ route('citizen.complaints.index') }}"><i class="fas fa-bullhorn"></i><span>{{ $t('citizen_complaints') }}</span></a>
                        @endif
                    @endauth
                </nav>
                
                @if(auth()->check() && auth()->user()->role === 'citizen')
                <div class="mt-auto px-3 pb-3 sidebar-bottom-actions">
                    <div class="section-title px-0 mb-2">Aksi Cepat</div>
                    <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary w-100 mb-2 py-2 d-flex align-items-center" style="font-size:0.82rem;">
                        <i class="fas fa-plus me-2"></i> <span>Laporan Baru</span>
                    </a>
                    <a href="{{ route('citizen.complaints.create') }}" class="btn btn-warning w-100 py-2 d-flex align-items-center" style="font-size:0.82rem;">
                        <i class="fas fa-bullhorn me-2"></i> <span>Keluhan Baru</span>
                    </a>
                </div>
                @endif
            </aside>

            <!-- Main Content Stage -->
            <main class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-circle-check fs-5 me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-triangle-exclamation fs-5 me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 loaded via Vite (app.js) -->

    <!-- Theme & Interactivity Script -->
    <script>
        // Auto dismiss flash alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Theme Switcher & Persistence
        (function() {
            const root = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const initialFromServer = root.classList.contains('dark') ? 'dark' : 'light';

            const applyTheme = (theme) => {
                if (theme === 'dark') {
                    root.classList.add('dark');
                    if (themeIcon) { themeIcon.className = 'fas fa-sun text-warning'; }
                    if (themeText) { themeText.textContent = 'Dark'; }
                } else {
                    root.classList.remove('dark');
                    if (themeIcon) { themeIcon.className = 'fas fa-moon text-primary'; }
                    if (themeText) { themeText.textContent = 'Light'; }
                }
            };

            let saved = localStorage.getItem('theme') || initialFromServer || 'light';
            applyTheme(saved);

            const persistTheme = async (theme) => {
                try {
                    const isAuth = {{ auth()->check() ? 'true' : 'false' }};
                    if (!isAuth) return;

                    await fetch('{{ route('profile.settings.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': metaCsrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ theme: theme, language: '{{ $appLang ?? 'id' }}' })
                    });
                } catch (e) {
                    console.warn('Failed to persist theme:', e);
                }
            };

            if (themeToggle) {
                themeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    saved = (saved === 'dark') ? 'light' : 'dark';
                    localStorage.setItem('theme', saved);
                    applyTheme(saved);
                    persistTheme(saved);
                });
            }
        })();

        // Mobile Sidebar Drawer Toggle
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebar');
        if (navbarToggler && sidebar) {
            navbarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    </script>

    @stack('modals')
    @yield('scripts')
</body>
</html>
