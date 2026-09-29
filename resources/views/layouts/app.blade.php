<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SynapseGov') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans (Optimized) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #b71c1c;
            --brand-primary-hover: #991b1b;
            --brand-secondary: #333333;
            --brand-accent: #f59e0b;
            --brand-gradient: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
            --hero-gradient: linear-gradient(135deg, #7f1d1d 0%, #b71c1c 50%, #dc2626 100%);
            --bg-canvas: #f8fafc;
            --card-bg: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            --card-border: rgba(0, 0, 0, 0.1);
            --text-main: #000000;
            --text-muted: #334155;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Inter', sans-serif;
            background-color: var(--bg-canvas);
            background-image: 
                radial-gradient(at 0% 0%, rgba(183, 28, 28, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(211, 47, 47, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .navbar {
            background: var(--card-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            padding: 0.75rem 1.5rem;
        }

        .navbar-brand,
        .navbar-brand:hover,
        .navbar-brand:focus,
        .navbar-brand:active,
        .navbar-brand * {
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--text-main) !important;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            letter-spacing: -0.02em;
            text-decoration: none !important;
            text-decoration-line: none !important;
            border-bottom: none !important;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            background: var(--brand-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(183, 28, 28, 0.35);
        }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--brand-primary) !important;
            background: rgba(183, 28, 28, 0.05);
        }

        .btn-primary {
            background: var(--brand-gradient) !important;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            box-shadow: 0 4px 12px rgba(183, 28, 28, 0.25);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(183, 28, 28, 0.4);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}?v={{ filemtime(public_path('css/navigation.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/public-ui.css') }}?v={{ filemtime(public_path('css/public-ui.css')) }}">
</head>
<body class="public-ui">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <div class="container">
                <!-- compact brand: icon only, title available on hover -->
                <button class="site-menu-toggle" type="button" data-site-menu aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Buka navigasi"><i class="fas fa-bars" aria-hidden="true"></i> Menu</button>
                    <a class="navbar-brand text-decoration-none" href="{{ route('landing') }}" title="SynapseGov" style="text-decoration: none !important;">
                        <div class="brand-icon-box" style="text-decoration: none !important;">
                            <i class="fas fa-bolt-lightning" style="text-decoration: none !important;"></i>
                        </div>
                        <span style="text-decoration: none !important;">Synapse<span style="background: var(--brand-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none !important;">Gov</span></span>
                    </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">
                                            <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus me-1"></i>{{ __('Register') }}
                                        </a>
                                    </li>
                                @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user me-1"></i>
                                    {{ Auth::user()->name }}
                                    <span class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                    <hr class="dropdown-divider">
                                    <form action="{{ route('logout') }}" method="POST" class="px-3 py-1 m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}</button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
<script src="{{ asset('js/navigation.js') }}?v={{ filemtime(public_path('js/navigation.js')) }}" defer></script>
</body>
</html>
