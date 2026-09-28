<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SynapseGov — Platform Aspirasi Publik</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-glow: rgba(37, 99, 235, 0.05);
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --accent: #2563eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            font-family: var(--font-main);
            line-height: 1.5;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Abstract Background Elements */
        .ambient-light {
            position: fixed;
            top: -20vh;
            left: 50%;
            transform: translateX(-50%);
            width: 80vw;
            height: 50vh;
            background: radial-gradient(ellipse at center, rgba(255,255,255,0.04) 0%, rgba(0,0,0,0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1.5rem 4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .logo {
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: -0.02em;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: '';
            display: block;
            width: 12px;
            height: 12px;
            background: var(--accent);
            border-radius: 3px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--text-main);
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-text {
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--accent);
            color: #ffffff;
            padding: 0.6rem 1.4rem;
            border-radius: 100px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 2rem;
            background: var(--accent-glow);
        }

        .hero h1 {
            font-size: clamp(3rem, 6vw, 5.5rem);
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            max-width: 900px;
        }

        .hero p {
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 3rem;
            font-weight: 400;
        }

        /* Bento Grid Section */
        .bento-section {
            padding: 8rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
            letter-spacing: -0.03em;
            margin-bottom: 4rem;
            text-align: center;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 300px);
            gap: 1.5rem;
        }

        .bento-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .bento-card:hover {
            border-color: var(--accent);
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
        }

        .bento-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.02) 0%, transparent 100%);
            pointer-events: none;
        }

        .bento-large {
            grid-column: span 2;
        }

        .bento-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .bento-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 80%;
        }

        .bento-visual {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-direction: column;
        }

        .metric-value {
            font-size: 4rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1;
        }

        /* Minimal Footer */
        footer {
            border-top: 1px solid var(--border-color);
            padding: 3rem 4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 900px) {
            nav { padding: 1.5rem 2rem; }
            .nav-links { display: none; }
            .bento-section { padding: 4rem 2rem; }
            .bento-grid { 
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }
            .bento-large { grid-column: span 1; }
            .bento-card { min-height: 280px; }
            footer {
                flex-direction: column;
                gap: 1rem;
                padding: 2rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-light"></div>

    <nav>
        <a href="/" class="logo">SynapseGov</a>
        <div class="nav-links">
            <a href="#platform">Platform</a>
            <a href="#features">Keunggulan</a>
        </div>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-text">Masuk</a>
            <a href="{{ route('register') }}" class="btn-primary">Mulai Lapor</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-badge">Akselerasi Pelayanan Publik</div>
        <h1>Suara warga.<br>Aksi nyata.</h1>
        <p>Platform e-Government minimalis yang menghubungkan aspirasi masyarakat dengan Organisasi Perangkat Daerah melalui sistem penanganan yang terukur dan transparan.</p>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('register') }}" class="btn-primary">Buat Laporan</a>
        </div>
    </section>

    <section class="bento-section" id="platform">
        <h2 class="section-title">Arsitektur Digital Terpadu</h2>
        
        <div class="bento-grid">
            <div class="bento-card bento-large">
                <div>
                    <h3>Automasi Resolusi</h3>
                    <p>Sistem secara mandiri memantau durasi penanganan (SLA) dan mengaktifkan protokol eskalasi jika tenggat terlewati.</p>
                </div>
                <div class="bento-visual">
                    <!-- Abstract representation instead of stickers -->
                    <div style="width: 100%; height: 2px; background: rgba(255,255,255,0.1); margin-top: 2rem; position: relative;">
                        <div style="position: absolute; top: 0; left: 0; height: 100%; width: 60%; background: #fff;"></div>
                    </div>
                </div>
            </div>

            <div class="bento-card">
                <div>
                    <h3>Isolasi Data</h3>
                    <p>Batas privasi antar Organisasi Perangkat Daerah dijaga ketat tanpa intervensi data silang.</p>
                </div>
                <div class="bento-visual">
                    <div style="width: 40px; height: 40px; border: 1px solid #fff; border-radius: 8px;"></div>
                </div>
            </div>

            <div class="bento-card">
                <div>
                    <h3>Jejak Forensik</h3>
                    <p>Setiap mutasi laporan direkam mutlak. Audit transparan meminimalkan manipulasi data.</p>
                </div>
                <div class="bento-visual">
                    <div style="display: flex; gap: 0.5rem;">
                        <div style="width: 4px; height: 16px; background: rgba(255,255,255,0.2);"></div>
                        <div style="width: 4px; height: 32px; background: rgba(255,255,255,0.5);"></div>
                        <div style="width: 4px; height: 24px; background: #fff;"></div>
                    </div>
                </div>
            </div>

            <div class="bento-card bento-large">
                <div>
                    <h3>Standar Keamanan</h3>
                    <p>Implementasi protokol OWASP untuk menangkal injeksi berbahaya, divalidasi dengan inspeksi MIME komprehensif.</p>
                </div>
                <div class="bento-visual" style="align-items: flex-start; justify-content: flex-end;">
                    <div class="metric-value">100%</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem;">Sistem terlindungi</div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div>&copy; {{ date('Y') }} SynapseGov. Hak cipta dilindungi.</div>
        <div>Dikembangkan untuk IT Days 2026.</div>
    </footer>
</body>
</html>
