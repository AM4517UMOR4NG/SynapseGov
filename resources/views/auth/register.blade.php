@extends('layouts.app')

@section('content')
<style>
    /* Comfortable High-End Register */
    :root {
        --bg-main: #f8fafc;
        --surface: #ffffff;
        --border-light: #e2e8f0;
        --text-pure: #000000;
        --text-dim: #334155;
        --accent: #b71c1c;
        --accent-hover: #991b1b;
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
        background: radial-gradient(circle at center, rgba(183, 28, 28, 0.05) 0%, transparent 60%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .auth-card {
        width: 100%;
        max-width: 1000px; /* Wider for register to fit 2-column form + left panel */
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: 24px;
        display: flex;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.1);
    }

    .auth-left {
        flex: 1;
        background: var(--accent);
        color: #ffffff;
        padding: 3rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
    }

    .back-btn {
        position: absolute;
        top: 2rem;
        left: 2rem;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background-color: rgba(255, 255, 255, 0.25);
        transform: translateX(-4px);
    }

    .auth-left-title {
        font-size: 2rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }
    
    .auth-left-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 4px;
        background-color: #eab308;
        margin: 0.5rem auto 0;
        border-radius: 2px;
    }

    .auth-left-subtitle {
        font-size: 0.95rem;
        line-height: 1.6;
        opacity: 0.9;
        max-width: 80%;
    }

    .auth-right {
        flex: 1.3;
        padding: 3.5rem 3rem;
        background: var(--surface);
    }

    @media (max-width: 900px) {
        .auth-card {
            flex-direction: column;
            max-width: 600px;
        }
        .auth-left {
            padding: 3rem 2rem;
        }
        .auth-right {
            padding: 2.5rem;
        }
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
        display: none; /* Hide label to mimic placeholder-only design */
    }

    .form-control {
        width: 100%;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.9rem 1.2rem;
        color: var(--text-pure);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        outline: none;
        font-family: inherit;
    }

    .form-control:focus {
        background: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(183, 28, 28, 0.15);
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

    /* Terms Modal CSS */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
    }

    .modal-content {
        background: var(--surface);
        width: 90%;
        max-width: 500px;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-height: 80vh;
        display: flex;
        flex-direction: column;
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-pure);
    }

    .modal-body {
        flex: 1;
        overflow-y: auto;
        color: var(--text-dim);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        padding-right: 0.5rem;
    }

    .modal-body p {
        margin-bottom: 0.75rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        border-top: 1px solid var(--border-light);
        padding-top: 1.5rem;
    }

    .btn-outline {
        padding: 0.6rem 1.2rem;
        border: 1px solid var(--border-light);
        background: transparent;
        border-radius: 8px;
        color: var(--text-dim);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-outline:hover {
        background: #f1f5f9;
        color: var(--text-pure);
    }
    
    .btn-primary-modal {
        background: var(--accent);
        color: white;
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary-modal:hover {
        background: var(--accent-hover);
    }
</style>

<div class="auth-container">
    <div class="ambient-glow"></div>
    
    <div class="auth-card">
        <div class="auth-left">
            <a href="{{ route('login') }}" class="back-btn" title="Kembali ke Halaman Login">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <!-- Logo Icon Representation -->
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                <path d="M2 17l10 5 10-5"></path>
                <path d="M2 12l10 5 10-5"></path>
            </svg>
            <h2 class="auth-left-title">Bergabung</h2>
            <p class="auth-left-subtitle">Silakan buat akun untuk mengakses sistem layanan publik dan aspirasi masyarakat.</p>
        </div>
        
        <div class="auth-right">
            <div class="auth-brand" style="text-align: left; margin-bottom: 2rem;">
                <h1 class="auth-title" style="color: var(--accent); margin-bottom: 0.5rem; position: relative; display: inline-block;">
                    Daftar Akun
                    <div style="position: absolute; bottom: -4px; left: 0; width: 40px; height: 3px; background-color: #eab308; border-radius: 2px;"></div>
                </h1>
            </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid-2">
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama Lengkap *">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Username / No. HP</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Username / No. HP">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Aktif <span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Aktif *">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_number" class="form-label">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                    <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required placeholder="NIK KTP (16 Digit) *">
                    @error('id_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Kata Sandi *">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Konfirmasi Sandi <span class="text-danger">*</span></label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Sandi *">
                </div>

                <div class="form-group">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input id="birth_date" type="text" onfocus="(this.type='date')" onblur="(this.value == '' ? this.type='text' : this.type='date')" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}" placeholder="Tanggal Lahir">
                    @error('birth_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
                        <option value="" disabled selected>Jenis Kelamin</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-group-full">
                    <label for="address" class="form-label">Alamat Domisili</label>
                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="2" placeholder="Alamat Lengkap / Domisili">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-wrap">
                    <input type="checkbox" id="terms-checkbox" name="terms" required>
                    <span>Saya menyetujui <a href="#" onclick="openTermsModal(event)">Syarat & Ketentuan</a>.</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                Daftar Akun Baru
            </button>
            
            <div class="auth-footer" style="margin-top: 1.5rem; margin-bottom: 2rem;">
                Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
            
            <div style="text-align: center; font-size: 0.75rem; color: var(--text-dim); margin-top: 2rem;">
                &copy; {{ date('Y') }} SynapseGov. Hak cipta dilindungi.
            </div>
        </form>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal-overlay" id="termsModal">
        <div class="modal-content">
            <div class="modal-header">Syarat & Ketentuan</div>
            <div class="modal-body">
                <p><strong>1. Kepatuhan Data</strong><br>Pengguna wajib memberikan data yang valid, akurat, dan dapat dipertanggungjawabkan kebenarannya.</p>
                <p><strong>2. Larangan Penggunaan</strong><br>Dilarang menggunakan sistem ini untuk tindakan penipuan, pencemaran nama baik, atau penyebaran berita bohong (hoaks).</p>
                <p><strong>3. Verifikasi Laporan</strong><br>Semua laporan dan aspirasi yang masuk akan diverifikasi terlebih dahulu sebelum diproses oleh pihak instansi terkait.</p>
                <p><strong>4. Privasi & Kerahasiaan</strong><br>Identitas pelapor akan dijaga kerahasiaannya sesuai dengan standar perlindungan data privasi, kecuali diminta secara sah oleh lembaga penegak hukum.</p>
                <p><strong>5. Etika Komunikasi</strong><br>Pengguna tidak diperkenankan menggunakan kata-kata kasar, ujaran kebencian, atau unsur SARA dalam isi laporan maupun komentar.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeTermsModal()">Tutup</button>
                <button type="button" class="btn-primary-modal" onclick="acceptTerms()">Terima & Setuju</button>
            </div>
        </div>
    </div>

    <script>
        function openTermsModal(event) {
            event.preventDefault();
            const modal = document.getElementById('termsModal');
            modal.style.display = 'flex';
            // Small timeout to allow display:flex to apply before adding opacity class
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function closeTermsModal() {
            const modal = document.getElementById('termsModal');
            modal.classList.remove('active');
            // Wait for CSS transition before hiding
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function acceptTerms() {
            document.getElementById('terms-checkbox').checked = true;
            closeTermsModal();
        }
    </script>
@endsection