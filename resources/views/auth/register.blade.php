@extends('layouts.app')

@section('content')
<style>
    /* Comfortable High-End Register */
    :root {
        --bg-main: #f8fafc;
        --surface: #ffffff;
        --border-light: #e2e8f0;
        --text-pure: #0f172a;
        --text-dim: #64748b;
        --accent: #2563eb;
        --accent-hover: #1d4ed8;
        --error: #ef4444;
    }

    body {
        background-color: var(--bg-main) !important;
        margin: 0 !important;
        padding: 0 !important;
        min-height: 100vh;
        color: var(--text-pure);
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }

    .navbar {
        display: none !important;
    }

    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 2rem;
    }

    .ambient-glow {
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle at center, rgba(37, 99, 235, 0.05) 0%, transparent 60%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .auth-card {
        width: 100%;
        max-width: 640px; /* Wider for register */
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        position: relative;
        z-index: 1;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.05);
    }

    .auth-brand {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .auth-brand a {
        color: var(--text-pure);
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .auth-brand a::before {
        content: '';
        display: block;
        width: 12px;
        height: 12px;
        background: var(--accent);
        border-radius: 3px;
    }

    .auth-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .auth-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
        color: var(--text-pure);
    }

    .auth-subtitle {
        font-size: 0.9rem;
        color: var(--text-dim);
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem 1.5rem;
    }
    
    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-pure);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        color: var(--text-pure);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        outline: none;
        font-family: inherit;
    }

    .form-control:focus {
        background: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control.is-invalid {
        border-color: var(--error);
        background: #fef2f2;
    }

    .invalid-feedback {
        color: var(--error);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: block;
    }

    .checkbox-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-dim);
        cursor: pointer;
        font-size: 0.85rem;
    }

    .checkbox-wrap input[type="checkbox"] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
    }
    
    .checkbox-wrap a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
    }
    .checkbox-wrap a:hover {
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        background: var(--accent);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.9rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        margin-top: 1rem;
    }

    .btn-submit:hover {
        background: var(--accent-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .auth-footer {
        margin-top: 2rem;
        text-align: center;
        font-size: 0.85rem;
        color: var(--text-dim);
    }

    .auth-footer a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-grid-2 { grid-template-columns: 1fr; gap: 0; }
        .form-group-full { grid-column: span 1; }
        .auth-card { padding: 2.5rem 1.5rem; }
    }
</style>

<div class="auth-container">
    <div class="ambient-glow"></div>
    
    <div class="auth-card">
        <div class="auth-brand">
            <a href="{{ route('landing') }}">SynapseGov</a>
        </div>

        <div class="auth-header">
            <h1 class="auth-title">Registrasi Akun</h1>
            <p class="auth-subtitle">Buat akun warga untuk mulai menggunakan layanan.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama sesuai KTP">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Username / No. HP</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx / username">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Aktif <span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_number" class="form-label">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                    <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required placeholder="16 digit NIK">
                    @error('id_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Konfirmasi Sandi <span class="text-danger">*</span></label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                </div>

                <div class="form-group">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-group-full">
                    <label for="address" class="form-label">Alamat Domisili</label>
                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="2" placeholder="Nama jalan, RT/RW, kelurahan, kecamatan">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-wrap">
                    <input type="checkbox" required>
                    <span>Saya menyetujui <a href="#">Syarat & Ketentuan</a>.</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                Daftar Akun Baru
            </button>
            
            <div class="auth-footer">
                Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection