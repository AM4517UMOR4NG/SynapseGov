@extends('layouts.app')

@section('content')
<style>
    /* Fullscreen High-End Register Portal */
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

    .register-wrapper {
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
    .register-hero {
        flex: 0.9;
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.8) 0%, rgba(11, 18, 32, 0.95) 100%);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
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

    .hero-headline {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.03em;
        color: #ffffff;
        margin-bottom: 1.25rem;
    }

    .hero-subhead {
        font-size: 1rem;
        color: #94a3b8;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    /* Right Form Section */
    .register-form-area {
        flex: 1.2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2.5rem;
        position: relative;
    }

    .register-glass-card {
        width: 100%;
        max-width: 640px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2.5rem;
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

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-group-custom {
        margin-bottom: 1rem;
    }

    .form-group-custom.full-width {
        grid-column: span 2;
    }

    .form-group-custom label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #cbd5e1;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .input-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-box input, .input-box select, .input-box textarea {
        width: 100%;
        background: rgba(7, 11, 20, 0.6);
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        color: #ffffff;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        outline: none;
    }

    .input-box select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }

    .input-box input:focus, .input-box select:focus, .input-box textarea:focus {
        border-color: #38bdf8;
        background: rgba(7, 11, 20, 0.85);
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }

    .btn-submit-register {
        width: 100%;
        padding: 0.85rem;
        background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.55);
        background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%);
    }

    .card-footer-auth {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .card-footer-auth a {
        color: #38bdf8;
        font-weight: 700;
        text-decoration: none;
    }

    .card-footer-auth a:hover {
        color: #60a5fa;
        text-decoration: underline;
    }

    .error-feedback {
        color: #f87171;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 991px) {
        .register-hero { display: none; }
        .form-grid-2 { grid-template-columns: 1fr; }
        .form-group-custom.full-width { grid-column: span 1; }
        .register-form-area { padding: 2rem 1.25rem; }
    }
</style>

<div class="register-wrapper">
    <!-- Left Hero Section -->
    <div class="register-hero">
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
                <i class="fas fa-user-plus me-1"></i>
                <span>Pendaftaran Akun Warga Baru</span>
            </div>
            <h1 class="hero-headline">Suara Anda Adalah<br>Perubahan Kota.</h1>
            <p class="hero-subhead">
                Daftarkan akun resmi kependudukan Anda untuk menyampaikan laporan fasilitas umum, keluhan layanan dinas, dan memantau penyelesaian secara transparan.
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; font-size: 0.9rem;">
                    <i class="fas fa-check-circle text-info"></i>
                    <span>Verifikasi data kependudukan terenkripsi</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; font-size: 0.9rem;">
                    <i class="fas fa-check-circle text-info"></i>
                    <span>Notifikasi real-time penugasan OPD</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; color: #cbd5e1; font-size: 0.9rem;">
                    <i class="fas fa-check-circle text-info"></i>
                    <span>Kerahasiaan identitas pelapor terjamin</span>
                </div>
            </div>
        </div>

        <div style="font-size: 0.8rem; color: #64748b;">
            &copy; {{ date('Y') }} SynapseGov — Mens et Corpus IT Days 2026.
        </div>
    </div>

    <!-- Right Register Form Section -->
    <div class="register-form-area">
        <div class="register-glass-card">
            <div class="form-header-title">Registrasi Akun</div>
            <div class="form-header-subtitle">Lengkapi formulir berikut untuk membuat akun baru</div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group-custom">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-box">
                            <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama sesuai KTP">
                        </div>
                        @error('name')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="phone">Username / No. HP</label>
                        <div class="input-box">
                            <input id="phone" type="text" class="@error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx / username">
                        </div>
                        @error('phone')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="email">Email Aktif <span class="text-danger">*</span></label>
                        <div class="input-box">
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="id_number">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                        <div class="input-box">
                            <input id="id_number" type="text" class="@error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required placeholder="16 digit NIK">
                        </div>
                        @error('id_number')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="password">Kata Sandi <span class="text-danger">*</span></label>
                        <div class="input-box">
                            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="password-confirm">Konfirmasi Sandi <span class="text-danger">*</span></label>
                        <div class="input-box">
                            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="birth_date">Tanggal Lahir</label>
                        <div class="input-box">
                            <input id="birth_date" type="date" class="@error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}">
                        </div>
                        @error('birth_date')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="gender">Jenis Kelamin</label>
                        <div class="input-box">
                            <select id="gender" class="@error('gender') is-invalid @enderror" name="gender">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        @error('gender')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group-custom full-width">
                        <label for="address">Alamat Domisili</label>
                        <div class="input-box">
                            <textarea id="address" class="@error('address') is-invalid @enderror" name="address" rows="2" placeholder="Nama jalan, RT/RW, kelurahan, kecamatan">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <div class="error-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin: 0.75rem 0 0.5rem 0; font-size: 0.82rem; color: #94a3b8;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" required style="accent-color: #2563eb;">
                        <span>Saya menyetujui <a href="#" style="color: #38bdf8;">Syarat & Ketentuan</a> pelaporan SynapseGov.</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit-register">
                    <span>Daftar Akun Baru</span>
                    <i class="fas fa-user-plus"></i>
                </button>

                <div class="card-footer-auth">
                    Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection