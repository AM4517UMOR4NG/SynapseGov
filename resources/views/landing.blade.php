<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SynapseGov — Platform e-Government Pengaduan & Aspirasi Masyarakat</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <div class="logo-icon"><i class="fas fa-bolt-lightning"></i></div>
                <div class="logo-text">
                    <span class="logo-main">Synapse<span class="logo-accent">Gov</span></span>
                    <span class="logo-sub">e-Government Civic System</span>
                </div>
            </div>
            <div class="nav-links">
                <a href="#home" class="nav-link active">Beranda</a>
                <a href="#features" class="nav-link">Keunggulan</a>
                <a href="#how-it-works" class="nav-link">Alur Kerja</a>
                <a href="#stats" class="nav-link">Statistik</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Mulai Lapor <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg">
            <div class="hero-circle circle-1"></div>
            <div class="hero-circle circle-2"></div>
            <div class="hero-circle circle-3"></div>
        </div>
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="pulse-dot"></span>
                    <span>Platform e-Government Terintegrasi SPBE</span>
                </div>
                <h1 class="hero-title">
                    Aspirasi Rakyat,<br>
                    <span class="gradient-text">Aksi Cepat Birokrasi</span>
                </h1>
                <p class="hero-description">
                    Jembatan digital modern penghubung suara masyarakat dengan Organisasi Perangkat Daerah (OPD). 
                    Dilengkapi penegakan batas waktu penanganan otomatis (SLA), audit log forensik, dan pelacakan tiket transparan.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <span>Buat Laporan Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#how-it-works" class="btn-hero-secondary">
                        <i class="fas fa-play-circle"></i>
                        <span>Pelajari Alur Disposisi</span>
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">SLA Enforced</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">Multi-OPD</div>
                        <div class="stat-label">Boundary Guard</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Tracking Real-time</div>
                    </div>
                </div>
            </div>

            <!-- Hero Interactive Visual Cards -->
            <div class="hero-image">
                <div class="floating-card card-1">
                    <div class="card-icon-wrap blue"><i class="fas fa-clipboard-check"></i></div>
                    <div class="card-content">
                        <div class="card-title">Tiket Terverifikasi</div>
                        <div class="card-desc">Disposisi ke Dinas PUPR</div>
                    </div>
                    <span class="badge-status-pill success">Verified</span>
                </div>
                <div class="floating-card card-2">
                    <div class="card-icon-wrap amber"><i class="fas fa-stopwatch"></i></div>
                    <div class="card-content">
                        <div class="card-title">Target SLA Terpantau</div>
                        <div class="card-desc">Sisa Waktu: 18 Jam</div>
                    </div>
                    <span class="badge-status-pill warning">Active</span>
                </div>
                <div class="floating-card card-3">
                    <div class="card-icon-wrap purple"><i class="fas fa-user-shield"></i></div>
                    <div class="card-content">
                        <div class="card-title">Privasi Terisolasi</div>
                        <div class="card-desc">Catatan Internal Terlindungi</div>
                    </div>
                    <span class="badge-status-pill secure"><i class="fas fa-lock"></i> Protected</span>
                </div>
                <div class="hero-illustration">
                    <div class="illustration-bg"></div>
                    <i class="fas fa-building-columns illustration-icon"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Arsitektur & Inovasi</span>
                <h2 class="section-title">Fitur Mutakhir Berstandar Tata Kelola Digital</h2>
                <p class="section-description">Dirancang sesuai standar SPBE Nasional dengan keunggulan logika otomasi dan keamanan berlapis</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon blue"><i class="fas fa-stopwatch-20"></i></div>
                    <h3 class="feature-title">Automated SLA Engine</h3>
                    <p class="feature-description">Deteksi keterlambatan penanganan secara otomatis. Tiket melewati batas langsung memicu eskalasi ke pimpinan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon purple"><i class="fas fa-sitemap"></i></div>
                    <h3 class="feature-title">Multi-Tenant OPD Boundary</h3>
                    <p class="feature-description">Isolasi data ketat antar dinas. Staf suatu dinas tidak dapat melihat atau memanipulasi aduan dinas lain.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon green"><i class="fas fa-user-lock"></i></div>
                    <h3 class="feature-title">Dual-Channel Comment</h3>
                    <p class="feature-description">Pemisahan catatan rahasia koordinasi teknis dinas dari jawaban publik warga demi menjaga kerahasiaan operasional.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon orange"><i class="fas fa-shield-virus"></i></div>
                    <h3 class="feature-title">Anti-Malware Upload Guard</h3>
                    <p class="feature-description">Validasi MIME Type dan pemblokiran ekstensi berbahaya (.php, .sh) dengan proteksi double-extension.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon red"><i class="fas fa-clock-rotate-left"></i></div>
                    <h3 class="feature-title">Immutable Audit Trail</h3>
                    <p class="feature-description">Pencatatan forensik setiap mutasi status, disposisi, dan aktor penanggung jawab tanpa celah manipulasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon cyan"><i class="fas fa-file-invoice"></i></div>
                    <h3 class="feature-title">Official PDF & CSV Export</h3>
                    <p class="feature-description">Cetak berkas bukti laporan resmi berstempel digital dan ekspor data kuantitatif CSV siap analisis pimpinan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Alur Disposisi</span>
                <h2 class="section-title">4 Tahap Penanganan Transparan</h2>
                <p class="section-description">Setiap langkah diikat oleh sistem status mesin yang transparan dan dapat dipantau langsung</p>
            </div>
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="fas fa-pen-nib"></i></div>
                    <h3 class="step-title">Pengajuan Laporan</h3>
                    <p class="step-description">Warga mengisi formulir laporan atau keluhan lengkap dengan lampiran bukti.</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                    <h3 class="step-title">Verifikasi Admin</h3>
                    <p class="step-description">Admin utama memvalidasi kelayakan berkas dan mendisposisikan ke dinas (OPD) terkait.</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="fas fa-user-gear"></i></div>
                    <h3 class="step-title">Penanganan Lapangan</h3>
                    <p class="step-description">Kepala dinas menugaskan staf teknis untuk menindaklanjuti dan mengunggah bukti pengerjaan.</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="fas fa-circle-check"></i></div>
                    <h3 class="step-title">Penyelesaian & Arsip</h3>
                    <p class="step-description">Hasil dikonfirmasi oleh pimpinan, tiket ditutup, dan warga menerima pemberitahuan resmi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="stats-bg"></div>
        <div class="section-container">
            <div class="stats-content">
                <div class="stats-left">
                    <span class="section-badge light">Komitmen Layanan</span>
                    <h2 class="stats-title">Transformasi Digital Nyata Pelayanan Publik</h2>
                    <p class="stats-description">Menghapus stigma birokrasi lambat dengan sistem yang mengikat kepastian waktu dan keterbukaan informasi publik.</p>
                    <a href="{{ route('register') }}" class="btn-stats">
                        Mulai Bergabung <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stats-right">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-users-viewfinder"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">4 Peran</div>
                            <div class="stat-text">Hierarki Hak Akses Terpisah</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-gauge-high"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">&lt; 24 Jam</div>
                            <div class="stat-text">Standar Respon Awal</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-shield-halved"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">100%</div>
                            <div class="stat-text">OWASP Security Compliance</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-award"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">IT Days '26</div>
                            <div class="stat-text">Web Development Project</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="section-container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title">Siap Bersinergi Membangun Daerah?</h2>
                    <p class="cta-description">Sampaikan laporan permasalahan di sekitar Anda dan kawal proses penyelesaiannya secara terbuka.</p>
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn-cta-primary">
                            Daftar Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn-cta-secondary">Masuk ke Akun Anda</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="section-container">
            <div class="footer-content">
                <div class="footer-col brand-col">
                    <div class="footer-logo">
                        <div class="logo-icon"><i class="fas fa-bolt-lightning"></i></div>
                        <div class="logo-text">
                            <span class="logo-main">Synapse<span class="logo-accent">Gov</span></span>
                            <span class="logo-sub">e-Government Civic System</span>
                        </div>
                    </div>
                    <p class="footer-desc">Platform tata kelola penanganan laporan dan pengaduan publik yang akuntabel, terikat SLA, dan berorientasi pada kepuasan masyarakat.</p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#features">Keunggulan Sistem</a></li>
                        <li><a href="#how-it-works">Alur Penanganan</a></li>
                        <li><a href="#stats">Metrik Kinerja</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Kontak & Dukungan</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope text-primary"></i> <span>support@synapsegov.id</span></li>
                        <li><i class="fas fa-building text-primary"></i> <span>Layanan Aspirasi Pemerintah Terpadu</span></li>
                        <li><i class="fas fa-shield-alt text-primary"></i> <span>IT Days 2026 Universitas Sanata Dharma</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 <strong>SynapseGov</strong>. Hak Cipta Dilindungi Undang-Undang. Dikembangkan untuk IT Days 2026.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
