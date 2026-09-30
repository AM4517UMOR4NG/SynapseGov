<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lacak status laporan atau keluhan Anda di SynapseGov dengan nomor tiket.">
    <meta name="robots" content="noindex">
    <title>Lacak Tiket — SynapseGov</title>
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
    <header class="tp-simple-header">
        <nav class="home-container" aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="home-brand"><span class="brand-mark" aria-hidden="true">S</span>SynapseGov<span class="brand-dot">.</span></a>
            <a href="{{ url('/') }}#transparansi" class="tp-back">← Kembali ke beranda</a>
        </nav>
    </header>
    <main id="main" class="home-container tp-track-page">
        <span class="home-eyebrow">LACAK TIKET</span>
        <h1>Pantau status laporan Anda.</h1>
        <p>Masukkan nomor tiket yang Anda terima saat mengirim laporan (<strong>RPT-…</strong>) atau keluhan (<strong>CMP-…</strong>).</p>

        <form class="tp-form" action="{{ route('public.track') }}" method="GET" role="search">
            <label for="track-ticket" class="visually-hidden">Nomor tiket</label>
            <input id="track-ticket" name="tiket" type="text" value="{{ $ticketNo }}" placeholder="RPT-20261001-AB12CD" autocomplete="off" required @if(!$ticket) autofocus @endif>
            <button type="submit">Lacak</button>
        </form>

        @if($error)
            <div class="tp-alert" role="alert">{{ $error }}</div>
        @endif

        @if($ticket)
            @php
                $chipClass = match (true) {
                    $ticket['status'] === 'rejected' => 'is-rejected',
                    $ticket['step'] === 4 => 'is-done',
                    default => 'is-progress',
                };
                $slaLabels = [
                    'on_time' => ['Selesai tepat waktu', 'is-done'],
                    'late' => ['Selesai melewati tenggat', 'is-progress'],
                    'within' => ['Dalam tenggat', ''],
                    'overdue' => ['Melewati tenggat', 'is-overdue'],
                ];
                $steps = ['Diterima', 'Diverifikasi', 'Ditangani', 'Selesai'];
            @endphp
            <section class="tp-result" aria-labelledby="ticket-heading" aria-live="polite">
                <div class="tp-result-head">
                    <div>
                        <span class="home-eyebrow">{{ strtoupper($ticket['type']) }}</span>
                        <h2 id="ticket-heading" class="tp-ticket-no">{{ $ticket['ticket_no'] }}</h2>
                    </div>
                    <span class="tp-chip {{ $chipClass }}">{{ $ticket['status_label'] }}</span>
                </div>

                @if($ticket['status'] === 'rejected')
                    <p class="tp-note">Tiket ini tidak dapat ditindaklanjuti. Pelapor dapat melihat alasan penolakan setelah masuk ke akunnya.</p>
                @else
                    <ol class="tp-steps" aria-label="Tahapan penanganan">
                        @foreach($steps as $index => $label)
                            @php $number = $index + 1; @endphp
                            <li class="{{ $number < $ticket['step'] || $ticket['step'] === 4 ? 'done' : ($number === $ticket['step'] ? 'current' : '') }}" @if($number === $ticket['step']) aria-current="step" @endif>{{ $label }}</li>
                        @endforeach
                    </ol>
                @endif

                <dl class="tp-meta">
                    <div><dt>OPD tujuan</dt><dd>{{ $ticket['department'] }}</dd></div>
                    <div><dt>Kategori</dt><dd>{{ $ticket['category'] }}</dd></div>
                    <div><dt>Diterima</dt><dd>{{ $ticket['created_at'] }} WIB</dd></div>
                    <div><dt>Pembaruan terakhir</dt><dd>{{ $ticket['updated_at'] }} WIB</dd></div>
                    @if($ticket['sla_state'])
                        <div>
                            <dt>Tenggat SLA</dt>
                            <dd>{{ $ticket['sla_due_at'] }} WIB <span class="tp-chip {{ $slaLabels[$ticket['sla_state']][1] }}">{{ $slaLabels[$ticket['sla_state']][0] }}</span></dd>
                        </div>
                    @endif
                </dl>

                <p class="tp-note">
                    Demi privasi, halaman publik ini hanya menampilkan status dan tahapan.
                    @auth
                        Detail lengkap dapat dilihat pelapor melalui <a href="{{ route('home') }}">dashboard</a>.
                    @else
                        Pelapor dapat melihat detail lengkap, catatan petugas, dan bukti penyelesaian setelah <a href="{{ route('login') }}">masuk</a>.
                    @endauth
                </p>
            </section>
        @endif
    </main>
    <footer class="home-footer home-container"><a href="{{ url('/') }}" class="home-brand"><span class="brand-mark" aria-hidden="true">S</span>SynapseGov.</a><span>© {{ date('Y') }} SynapseGov · IT Days 2026</span><a href="#main">Kembali ke atas ↑</a></footer>
</body>
</html>
