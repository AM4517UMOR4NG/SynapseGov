<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sampaikan laporan dan aspirasi masyarakat, lalu pantau tindak lanjutnya melalui SynapseGov.">
    <title>SynapseGov — Suara Warga, Aksi Nyata</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}?v={{ filemtime(public_path('css/navigation.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/public-ui.css') }}?v={{ filemtime(public_path('css/public-ui.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/transparency.css') }}?v={{ filemtime(public_path('css/transparency.css')) }}">
</head>
<body class="landing-ui home-page">
    <a class="skip-link" href="#main">Lewati ke konten</a>
    <header class="home-header">
        <nav aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="home-brand"><span class="brand-mark" aria-hidden="true">S</span>SynapseGov<span class="brand-dot">.</span></a>
            <button class="site-menu-toggle" type="button" data-site-menu aria-controls="landingNavigation" aria-expanded="false" aria-label="Buka navigasi">☰ <span>Menu</span></button>
            <div id="landingNavigation" class="site-navigation">
                <div class="home-links"><a href="#platform">Cara kerja</a><a href="#features">Layanan</a><a href="#transparansi">Transparansi</a><a href="{{ route('public.track') }}">Lacak tiket</a><a href="#questions">Pertanyaan</a></div>
                <div class="home-actions">
                    @auth
                        <a href="{{ route('home') }}" class="home-button small">Buka dashboard <span aria-hidden="true">↗</span></a>
                    @else
                        <a href="{{ route('login') }}" class="home-login">Masuk</a><a href="{{ route('register') }}" class="home-button small">Daftar akun <span aria-hidden="true">↗</span></a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>
    <main id="main">
        <section class="home-hero home-container" aria-labelledby="hero-title">
            <div class="hero-copy">
                <span class="home-eyebrow"><span class="live-dot"></span> RUANG ASPIRASI MASYARAKAT</span>
                <h1 id="hero-title">Perubahan baik,<br>dimulai dari<br><em>suara Anda.</em></h1>
                <p>Sampaikan persoalan di sekitar Anda. Terhubung dengan dinas terkait, dan ikuti perkembangan laporan dalam satu tempat.</p>
                <div class="hero-actions"><a href="{{ auth()->check() ? route('home') : route('register') }}" class="home-button">{{ auth()->check() ? 'Buka dashboard' : 'Mulai sampaikan laporan' }} <span aria-hidden="true">↗</span></a><a href="#platform" class="home-secondary">Lihat cara kerja <span aria-hidden="true">↓</span></a></div>
                <div class="hero-note"><span aria-hidden="true">✓</span> Laporan tersimpan <span aria-hidden="true">✓</span> Progres dapat dipantau</div>
            </div>
            <div class="hero-art" aria-label="Ilustrasi perjalanan laporan warga">
                <div class="art-grid" aria-hidden="true"></div>
                <span class="art-caption">WARGA TERHUBUNG. LAYANAN BERGERAK.</span>
                <div class="report-preview">
                    <div class="preview-top"><span><span class="preview-icon" aria-hidden="true">↗</span> Aspirasi warga</span><span class="example-label">CONTOH</span></div>
                    <div class="preview-body"><span class="preview-category">INFRASTRUKTUR</span><h2>Jalan yang lebih baik<br>untuk semua.</h2><p>Perbaikan fasilitas umum di lingkungan warga.</p><div class="preview-meta"><span>◎ &nbsp; Lingkungan sekitar</span><span class="preview-status">Sedang ditangani</span></div></div>
                    <ol class="preview-progress"><li class="done"><span>✓</span>Diterima</li><li class="current"><span>2</span>Ditangani</li><li><span>3</span>Selesai</li></ol>
                </div>
                <div class="art-note"><span class="note-icon" aria-hidden="true">✓</span><div><strong>Setiap suara berarti.</strong><span>Langkah kecil untuk layanan yang lebih baik.</span></div></div>
                <span class="art-bottom">DARI ASPIRASI MENJADI AKSI <span aria-hidden="true">↗</span></span>
            </div>
        </section>
        <div class="service-strip"><div class="home-container"><span>Satu ruang untuk<br><strong>layanan yang lebih dekat.</strong></span><span>01 &nbsp; Sampaikan laporan</span><span>02 &nbsp; Terhubung ke dinas</span><span>03 &nbsp; Pantau tindak lanjut</span></div></div>
        <section id="platform" class="home-section home-container">
            <div class="section-heading"><div><span class="home-eyebrow">CARA KERJA</span><h2>Mudah disampaikan.<br>Jelas perkembangannya.</h2></div><p>Dari laporan pertama hingga tindak lanjut, Anda dapat mengikuti prosesnya melalui akun Anda.</p></div>
            <div class="steps-grid"><article><span class="step-number">01</span><h3>Ceritakan persoalannya</h3><p>Tulis laporan atau keluhan, tentukan lokasi dan dinas tujuan, lalu tambahkan lampiran yang relevan.</p></article><article><span class="step-number">02</span><h3>Laporan ditindaklanjuti</h3><p>Laporan ditinjau dan diteruskan kepada petugas untuk proses penanganan sesuai kebutuhan.</p></article><article><span class="step-number">03</span><h3>Ikuti perkembangannya</h3><p>Lihat status dan catatan penanganan. Lengkapi informasi ketika petugas memerlukan detail tambahan.</p></article></div>
        </section>
        <section id="features" class="home-section features-section"><div class="home-container">
            <div class="section-heading"><div><span class="home-eyebrow">LAYANAN UNTUK ANDA</span><h2>Lebih dekat dengan<br>solusi yang dibutuhkan.</h2></div><p>Tempat untuk menyampaikan persoalan, memberi masukan, dan melihat tindak lanjutnya.</p></div>
            <div class="features-grid"><article class="feature-featured"><span class="feature-symbol" aria-hidden="true">↗</span><h3>Laporan masyarakat</h3><p>Jalan rusak, fasilitas umum, atau persoalan lingkungan. Bantu dinas memahami kondisi di sekitar Anda.</p><a href="{{ auth()->check() ? route('home') : route('register') }}">Mulai buat laporan <span aria-hidden="true">→</span></a></article><article><span class="feature-symbol" aria-hidden="true">◎</span><h3>Keluhan & aspirasi</h3><p>Sampaikan pengalaman pelayanan dan masukan Anda untuk membantu perbaikan layanan publik.</p><a href="{{ auth()->check() ? route('home') : route('register') }}">Sampaikan aspirasi <span aria-hidden="true">→</span></a></article><article><span class="feature-symbol" aria-hidden="true">≡</span><h3>Riwayat dalam satu tempat</h3><p>Akses laporan, lampiran, dan status penanganan tanpa perlu mencatat prosesnya secara terpisah.</p><a href="{{ auth()->check() ? route('home') : route('login') }}">Lihat laporan saya <span aria-hidden="true">→</span></a></article></div>
        </div></section>
        @php $overall = $transparency['overall']; @endphp
        <section id="transparansi" class="home-section tp-section" aria-labelledby="transparency-title"><div class="home-container">
            <div class="section-heading"><div><span class="home-eyebrow">TRANSPARANSI LAYANAN</span><h2 id="transparency-title">Kinerja dinas,<br>terbuka untuk publik.</h2></div><p>Angka di bawah dihitung otomatis dari seluruh laporan warga, termasuk seberapa banyak yang tuntas sebelum tenggat SLA.</p></div>
            <div class="tp-kpis">
                <div class="tp-kpi"><span class="tp-kpi-label">Laporan diterima</span><span class="tp-kpi-value">{{ number_format($overall['total'], 0, ',', '.') }}</span><span class="tp-kpi-note">{{ number_format($overall['in_progress'], 0, ',', '.') }} sedang ditangani</span></div>
                <div class="tp-kpi"><span class="tp-kpi-label">Tuntas ditangani</span><span class="tp-kpi-value">@if($overall['completion_rate'] !== null){{ $overall['completion_rate'] }}<small>%</small>@else<span class="tp-muted">–</span>@endif</span><span class="tp-kpi-note">{{ number_format($overall['completed'], 0, ',', '.') }} laporan selesai</span></div>
                <div class="tp-kpi is-accent"><span class="tp-kpi-label">Selesai tepat waktu</span><span class="tp-kpi-value">@if($overall['on_time_rate'] !== null){{ $overall['on_time_rate'] }}<small>%</small>@else<span class="tp-muted">–</span>@endif</span><span class="tp-kpi-note">diselesaikan sebelum tenggat SLA</span></div>
            </div>
            <div class="tp-body">
                <div class="tp-table">
                    <div class="tp-row tp-head" aria-hidden="true"><span>Organisasi Perangkat Daerah</span><span class="tp-num">Laporan</span><span>Tuntas</span><span class="tp-num">Tepat waktu</span></div>
                    @forelse($transparency['departments'] as $dept)
                        <div class="tp-row">
                            <span class="tp-dept">{{ $dept['name'] }}</span>
                            <span class="tp-num" data-label="Laporan">{{ number_format($dept['total'], 0, ',', '.') }}</span>
                            <span class="tp-bar" data-label="Tuntas">
                                <span class="tp-bar-track" role="presentation"><span class="tp-bar-fill" style="width: {{ $dept['completion_rate'] ?? 0 }}%"></span></span>
                                <span class="tp-bar-value">{{ $dept['completion_rate'] !== null ? $dept['completion_rate'].'%' : '–' }}</span>
                            </span>
                            <span class="tp-num tp-ontime" data-label="Tepat waktu">{{ $dept['on_time_rate'] !== null ? $dept['on_time_rate'].'%' : '–' }}</span>
                        </div>
                    @empty
                        <p class="tp-empty">Belum ada OPD aktif.</p>
                    @endforelse
                    @if($overall['total'] === 0)
                        <p class="tp-empty">Belum ada laporan yang masuk. Statistik akan terisi otomatis begitu warga mulai melapor.</p>
                    @endif
                    <p class="tp-foot">Diperbarui {{ $transparency['generated_at'] }} WIB · "Tuntas" tidak menghitung laporan yang ditolak.</p>
                </div>
                <div class="tp-track-card">
                    <span class="home-eyebrow">LACAK TIKET</span>
                    <h3>Sudah melapor? Cek statusnya di sini.</h3>
                    <p>Masukkan nomor tiket yang Anda terima saat mengirim laporan atau keluhan.</p>
                    <form class="tp-form" action="{{ route('public.track') }}" method="GET" role="search">
                        <label for="landing-ticket" class="visually-hidden">Nomor tiket</label>
                        <input id="landing-ticket" name="tiket" type="text" placeholder="RPT-20261001-AB12CD" autocomplete="off" required>
                        <button type="submit">Lacak</button>
                    </form>
                    <span class="tp-privacy">Hanya status dan tahapan yang ditampilkan. Isi laporan dan identitas pelapor tetap terlindungi.</span>
                </div>
            </div>
        </div></section>
        <section id="questions" class="home-section home-container faq-section"><div><span class="home-eyebrow">SEBELUM MEMULAI</span><h2>Ada yang ingin<br>Anda ketahui?</h2><p>Beberapa hal untuk membantu Anda menyampaikan laporan.</p></div><div class="faq-list"><details><summary>Apa yang perlu disiapkan?</summary><p>Siapkan judul, penjelasan persoalan, lokasi jika relevan, serta lampiran pendukung. Pilih dinas yang sesuai dengan laporan Anda.</p></details><details><summary>Apakah saya perlu membuat akun?</summary><p>Ya. Akun digunakan untuk mengirim laporan dan melihat perkembangannya. Jika sudah memiliki akun, Anda dapat langsung masuk.</p></details><details><summary>Bagaimana cara melihat tindak lanjut?</summary><p>Masuk ke akun Anda, buka daftar laporan atau keluhan, lalu pilih detail untuk melihat status dan catatan penanganan yang tersedia.</p></details></div></section>
        <section class="home-container"><div class="closing-banner"><div><span class="home-eyebrow">BERSAMA, KITA MULAI.</span><h2>Lingkungan lebih baik.<br>Pelayanan lebih dekat.</h2></div><a href="{{ auth()->check() ? route('home') : route('register') }}" class="home-button light">{{ auth()->check() ? 'Ke dashboard Anda' : 'Sampaikan suara Anda' }} <span aria-hidden="true">↗</span></a></div></section>
    </main>
    <footer class="home-footer home-container"><a href="{{ url('/') }}" class="home-brand"><span class="brand-mark" aria-hidden="true">S</span>SynapseGov.</a><span>© {{ date('Y') }} SynapseGov · IT Days 2026</span><a href="#main">Kembali ke atas ↑</a></footer>
    <script src="{{ asset('js/navigation.js') }}?v={{ filemtime(public_path('js/navigation.js')) }}" defer></script>
</body>
</html>
