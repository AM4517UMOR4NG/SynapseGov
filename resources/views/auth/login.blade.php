@extends('layouts.app')

@section('content')
<style>
    /* Fullscreen High-End Login Portal */
    body {
        background: #070b14 !important;
        margin: 0 !important;
        padding-top: 0 !important;
        min-height: 100vh;
        overflow-x: hidden;
        color: #f8fafc;
    }

    .navbar {
        display: none !important;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: stretch;
        position: relative;
        background: #070b14;
        background-image: 
            radial-gradient(at 15% 20%, rgba(37, 99, 235, 0.18) 0px, transparent 45%),
            radial-gradient(at 85% 80%, rgba(14, 165, 233, 0.15) 0px, transparent 45%);
    }

    /* Left Showcase Panel */
    .login-hero {
        flex: 1.1;
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.8) 0%, rgba(11, 18, 32, 0.95) 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        padding: 4rem 4.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .login-hero::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
        top: -150px;
        left: -150px;
        pointer-events: none;
    }

    .hero-brand {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        text-decoration: none;
        position: relative;
        z-index: 2;
    }

    .hero-brand-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.5);
    }

    .hero-brand-title {
        font-weight: 800;
        font-size: 1.4rem;
        letter-spacing: -0.02em;
        color: #ffffff;
    }

    .hero-brand-title span {
        background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        background: rgba(37, 99, 235, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #38bdf8;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        width: fit-content;
    }

    .hero-badge .pulse-point {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 8px #10b981;
        animation: pulse 2s infinite;
    }

    .hero-headline {
        font-size: 2.75rem;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.03em;
        color: #ffffff;
        margin-bottom: 1.25rem;
    }

    .hero-subhead {
        font-size: 1.05rem;
        color: #94a3b8;
        line-height: 1.6;
        max-width: 520px;
        margin-bottom: 2.5rem;
    }

    /* Live Features Pill List */
    .hero-feature-cards {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .hero-feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 14px;
        backdrop-filter: blur(10px);
        transition: all 0.25s ease;
    }

    .hero-feature-item:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(56, 189, 248, 0.3);
        transform: translateX(4px);
    }

    .hero-feature-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .hero-feature-icon.blue { background: rgba(37, 99, 235, 0.2); color: #60a5fa; }
    .hero-feature-icon.emerald { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .hero-feature-icon.amber { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }

    .hero-feature-text h4 {
        margin: 0 0 0.2rem 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #f1f5f9;
    }

    .hero-feature-text p {
        margin: 0;
        font-size: 0.82rem;
        color: #94a3b8;
    }

    .hero-footer-note {
        font-size: 0.8rem;
        color: #64748b;
        position: relative;
        z-index: 2;
    }

    /* Right Form Section */
    .login-form-area {
        flex: 0.9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
    }

    .login-glass-card {
        width: 100%;
        max-width: 440px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2.75rem 2.25rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        position: relative;
        z-index: 2;
    }

    .form-header-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #ffffff;
        margin-bottom: 0.35rem;
    }

    .form-header-subtitle {
        font-size: 0.88rem;
        color: #94a3b8;
        margin-bottom: 1.75rem;
    }

    .form-group-custom {
        margin-bottom: 1.25rem;
    }

    .form-group-custom label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .input-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-box i.input-icon {
        position: absolute;
        left: 1rem;
        color: #64748b;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .input-box input {
        width: 100%;
        background: rgba(7, 11, 20, 0.6);
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        color: #ffffff;
        font-size: 0.92rem;
        transition: all 0.2s ease;
        outline: none;
    }

    .input-box input:focus {
        border-color: #38bdf8;
        background: rgba(7, 11, 20, 0.85);
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
    }

    .input-box input:focus + i.input-icon {
        color: #38bdf8;
    }

    .toggle-pass-btn {
        position: absolute;
        right: 1rem;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .toggle-pass-btn:hover {
        color: #38bdf8;
    }

    .form-extra-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1.25rem 0 1.5rem 0;
        font-size: 0.84rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        cursor: pointer;
        user-select: none;
    }

    .forgot-pass-link {
        color: #38bdf8;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .forgot-pass-link:hover {
        color: #60a5fa;
        text-decoration: underline;
    }

    .btn-submit-login {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.55);
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);
    }

    /* Quick Demo Switcher */
    .demo-accounts-card {
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .demo-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .demo-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .demo-pill {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        color: #cbd5e1;
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
        text-align: left;
    }

    .demo-pill:hover {
        background: rgba(37, 99, 235, 0.2);
        border-color: rgba(56, 189, 248, 0.4);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .card-footer-auth {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .card-footer-auth a {
        color: #38bdf8;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .card-footer-auth a:hover {
        color: #60a5fa;
        text-decoration: underline;
    }

    .error-feedback {
        color: #f87171;
        font-size: 0.78rem;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    @media (max-width: 991px) {
        .login-hero { display: none; }
        .login-form-area { padding: 2rem 1.25rem; }
    }
</style>

<div class="login-wrapper">
    <!-- Left Hero Section -->
    <div class="login-hero">
        <div>
            <a href="{{ route('home') }}" class="hero-brand">
                <div class="hero-brand-icon">
                    <i class="fas fa-bolt-lightning"></i>
                </div>
                <div class="hero-brand-title">Synapse<span>Gov</span></div>
            </a>
        </div>

        <div style="position: relative; z-index: 2; margin: 3rem 0;">
            <div class="hero-badge">
                <span class="pulse-point"></span>
                <span>e-Gov Portal Terverifikasi</span>
            </div>
            <h1 class="hero-headline">Transparansi Nyata,<br>Respons Cepat Warga.</h1>
            <p class="hero-subhead">
                Platform satu pintu pelaporan publik dan penanganan keluhan warga dengan SLA otomatis, pemisahan data multi-tenant OPD, dan audit trail transparan.
            </p>

            <div class="hero-feature-cards">
                <div class="hero-feature-item">
                    <div class="hero-feature-icon blue">
                        <i class="fas fa-gauge-high"></i>
                    </div>
                    <div class="hero-feature-text">
                        <h4>Automated SLA Escalation</h4>
                        <p>Penetapan tenggat otomatis 24–72 jam dengan deteksi breach real-time.</p>
                    </div>
                </div>

                <div class="hero-feature-item">
                    <div class="hero-feature-icon emerald">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="hero-feature-text">
                        <h4>Dual-Channel Privacy Guard</h4>
                        <p>Catatan internal OPD terisolasi dari tanggapan publik warga secara aman.</p>
                    </div>
                </div>

                <div class="hero-feature-item">
                    <div class="hero-feature-icon amber">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div class="hero-feature-text">
                        <h4>Multi-Tenant OPD Isolation</h4>
                        <p>Akses data terpartisi ketat per dinas tanpa risiko kebocoran silang.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-footer-note">
            &copy; {{ date('Y') }} SynapseGov — Mens et Corpus IT Days 2026. Hak cipta dilindungi.
        </div>
    </div>

    <!-- Right Login Form Section -->
    <div class="login-form-area">
        <div class="login-glass-card">
            <div class="form-header-title">Masuk ke Portal</div>
            <div class="form-header-subtitle">Silakan masukkan kredensial akun Anda</div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group-custom">
                    <label for="email">Alamat Email</label>
                    <div class="input-box">
                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="error-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label for="password">Kata Sandi</label>
                    <div class="input-box">
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-pass-btn" onclick="togglePasswordVisibility()" title="Lihat Sandi">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-extra-row">
                    <label class="checkbox-label" for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="accent-color: #2563eb;">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-pass-link">Lupa sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit-login">
                    <span>Masuk Sekarang</span>
                    <i class="fas fa-arrow-right"></i>
                </button>

                <!-- Quick Demo Login Switcher -->
                <div class="demo-accounts-card">
                    <div class="demo-title">
                        <i class="fas fa-bolt text-warning"></i>
                        Akses Demo Cepat (Juri / Penguji)
                    </div>
                    <div class="demo-grid">
                        <button type="button" class="demo-pill" onclick="fillCredentials('admingg@gmail.com', 'password')">
                            <i class="fas fa-shield-alt text-primary"></i>
                            <span>Admin GG</span>
                        </button>
                        <button type="button" class="demo-pill" onclick="fillCredentials('departhead@gmail.com', 'password')">
                            <i class="fas fa-user-tie text-info"></i>
                            <span>Kepala OPD</span>
                        </button>
                        <button type="button" class="demo-pill" onclick="fillCredentials('staff@gmail.com', 'password')">
                            <i class="fas fa-user-gear text-success"></i>
                            <span>Staff OPD</span>
                        </button>
                        <button type="button" class="demo-pill" onclick="fillCredentials('usergg@gmail.com', 'password')">
                            <i class="fas fa-user text-warning"></i>
                            <span>Warga (Citizen)</span>
                        </button>
                    </div>
                </div>

                <div class="card-footer-auth">
                    Belum memiliki akun warga? <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
        
        // Visual feedback highlight
        const emailInput = document.getElementById('email');
        emailInput.style.borderColor = '#38bdf8';
        emailInput.style.boxShadow = '0 0 0 4px rgba(56, 189, 248, 0.25)';
        setTimeout(() => {
            emailInput.style.borderColor = '';
            emailInput.style.boxShadow = '';
        }, 800);
    }
</script>
@endsection
