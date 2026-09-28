@extends('layouts.app')

@section('content')
<style>
    /* Comfortable High-End Login */
    :root {
        --bg-main: #f8fafc;
        --surface: #ffffff;
        --border-light: #e2e8f0;
        --text-pure: #000000;
        --text-dim: #334155;
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

    /* Hide default navbar from layouts.app if necessary, or just force the design */
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

    /* Subtle ambient background glow */
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
        max-width: 420px;
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

    .form-group {
        margin-bottom: 1.5rem;
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

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-dim);
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
    }

    .toggle-password:hover {
        color: var(--text-pure);
    }

    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        font-size: 0.85rem;
    }

    .checkbox-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-dim);
        cursor: pointer;
    }

    .checkbox-wrap input[type="checkbox"] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
    }

    .forgot-link {
        color: var(--accent);
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 500;
    }

    .forgot-link:hover {
        color: var(--accent-hover);
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
    }

    .btn-submit:hover {
        background: var(--accent-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .demo-section {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    .demo-title {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        text-align: center;
    }

    .demo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .demo-btn {
        background: #f1f5f9;
        border: 1px solid transparent;
        color: var(--text-dim);
        border-radius: 8px;
        padding: 0.6rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .demo-btn:hover {
        background: #e2e8f0;
        color: var(--text-pure);
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
</style>

<div class="auth-container">
    <div class="ambient-glow"></div>
    
    <div class="auth-card">
        <div class="auth-brand">
            <a href="{{ route('landing') }}">SynapseGov</a>
        </div>

        <div class="auth-header">
            <h1 class="auth-title">Selamat Datang</h1>
            <p class="auth-subtitle">Masuk untuk melanjutkan ke sistem.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="password-wrapper">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="toggle-password" onclick="togglePassword()">Tampilkan</button>
                </div>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth-options">
                <label class="checkbox-wrap" for="remember">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                Masuk ke Akun
            </button>
        </form>

        <div class="demo-section">
            <div class="demo-title">Kredensial Demo</div>
            <div class="demo-grid">
                <button type="button" class="demo-btn" onclick="fillDemo('admingg@gmail.com', 'password')">Admin</button>
                <button type="button" class="demo-btn" onclick="fillDemo('departhead@gmail.com', 'password')">Kepala OPD</button>
                <button type="button" class="demo-btn" onclick="fillDemo('staff@gmail.com', 'password')">Staff OPD</button>
                <button type="button" class="demo-btn" onclick="fillDemo('usergg@gmail.com', 'password')">Warga</button>
            </div>
        </div>

        <div class="auth-footer">
            Belum memiliki akun? <a href="{{ route('register') }}">Buat sekarang</a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passInput = document.getElementById('password');
        const toggleBtn = document.querySelector('.toggle-password');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            toggleBtn.textContent = 'Sembunyikan';
        } else {
            passInput.type = 'password';
            toggleBtn.textContent = 'Tampilkan';
        }
    }

    function fillDemo(email, password) {
        const emailInput = document.getElementById('email');
        const passInput = document.getElementById('password');
        
        emailInput.value = email;
        passInput.value = password;
        
        // Visual cue
        emailInput.style.borderColor = 'rgba(255, 255, 255, 0.5)';
        passInput.style.borderColor = 'rgba(255, 255, 255, 0.5)';
        setTimeout(() => {
            emailInput.style.borderColor = '';
            passInput.style.borderColor = '';
        }, 500);
    }
</script>
@endsection
