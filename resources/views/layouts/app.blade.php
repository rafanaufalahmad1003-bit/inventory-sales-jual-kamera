<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CameraStore') - Inventory & Sales</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --cs-primary: #2563eb;
            --cs-primary-dark: #1d4ed8;
            --cs-primary-light: #eff6ff;
            --cs-accent: #e11d48;
            --cs-sidebar-width: 250px;
            --cs-body-bg: #f8fafc;
            --cs-card-bg: #ffffff;
            --cs-text-main: #0f172a;
            --cs-text-muted: #64748b;
            --cs-border: #e2e8f0;
        }
        body {
            background-color: var(--cs-body-bg);
            color: var(--cs-text-main);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        /* Top Navbar */
        .cs-navbar {
            background: #ffffff !important;
            border-bottom: 1px solid var(--cs-border);
            padding: 0.65rem 1.25rem;
            z-index: 1040;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .cs-navbar .navbar-brand {
            color: #0f172a !important;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.3px;
        }
        .cs-navbar .nav-link {
            color: #475569 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .cs-navbar .nav-link:hover {
            color: var(--cs-primary) !important;
            background: var(--cs-primary-light);
        }

        /* Sidebar for internal staff/owner */
        .cs-sidebar {
            position: fixed;
            top: 57px;
            left: 0;
            width: var(--cs-sidebar-width);
            height: calc(100vh - 57px);
            background: #ffffff;
            border-right: 1px solid var(--cs-border);
            overflow-y: auto;
            transition: transform 0.25s ease;
            z-index: 1030;
            padding-top: 0.75rem;
        }
        .cs-sidebar .nav-link {
            color: #475569;
            padding: 0.65rem 1.25rem;
            font-size: 0.88rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .cs-sidebar .nav-link:hover {
            color: var(--cs-primary);
            background: #f1f5f9;
        }
        .cs-sidebar .nav-link.active {
            color: var(--cs-primary);
            background: var(--cs-primary-light);
            border-left-color: var(--cs-primary);
            font-weight: 600;
        }
        .cs-sidebar .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }
        .cs-sidebar .sidebar-heading {
            color: #94a3b8;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 1rem 1.25rem 0.35rem;
            font-weight: 700;
        }

        /* Main Content */
        .cs-main {
            padding: 1.75rem 2rem 3rem;
            min-height: calc(100vh - 57px);
        }
        .has-sidebar .cs-main {
            margin-left: var(--cs-sidebar-width);
        }

        /* Cards & Components */
        .card {
            background-color: var(--cs-card-bg);
            border: 1px solid var(--cs-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--cs-border);
        }

        /* Clean Table Style */
        .table {
            color: #1e293b;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--cs-border);
            padding: 0.75rem 1rem;
        }
        .table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Forms */
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #0f172a;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--cs-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* Footer */
        .cs-footer {
            background: #ffffff;
            border-top: 1px solid var(--cs-border);
            padding: 1rem;
            text-align: center;
            font-size: 0.82rem;
            color: #64748b;
        }
        .has-sidebar .cs-footer {
            margin-left: var(--cs-sidebar-width);
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            z-index: 1025;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 991.98px) {
            .cs-sidebar {
                transform: translateX(-100%);
            }
            .cs-sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.1);
            }
            .has-sidebar .cs-main, .has-sidebar .cs-footer {
                margin-left: 0 !important;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="{{ (auth()->check() && auth()->user()->isInternal()) ? 'has-sidebar' : '' }}">

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-lg cs-navbar fixed-top">
        <div class="container-fluid px-3">
            @if(auth()->check() && auth()->user()->isInternal())
            <button class="btn btn-light border btn-sm d-lg-none me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            @endif

            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                    <i class="bi bi-camera-fill fs-5"></i>
                </div>
                <span>CameraStore</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle small px-2 py-1" style="font-size:0.65rem;">Sistem Bisnis</span>
            </a>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    @auth
                        @if(auth()->user()->isInternal())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-1x2-fill me-1 text-primary"></i> Dashboard & Laporan Terpadu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('catalog.index') }}" target="_blank">
                                <i class="bi bi-shop me-1 text-secondary"></i> Buka Katalog Online <i class="bi bi-box-arrow-up-right small ms-1" style="font-size:0.75rem;"></i>
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('catalog.index') }}">
                                <i class="bi bi-shop me-1 text-primary"></i> Katalog Kamera
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('catalog.myOrders') }}">
                                <i class="bi bi-bag-check me-1 text-success"></i> Pesanan Saya
                            </a>
                        </li>
                        @endif
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catalog.index') }}">
                            <i class="bi bi-shop me-1 text-primary"></i> Katalog Kamera
                        </a>
                    </li>
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary-subtle text-primary border border-primary-subtle rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:34px;height:34px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="d-none d-md-block text-start lh-sm">
                                <div class="fw-semibold text-dark small">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:0.72rem;">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border">
                            <li><span class="dropdown-item-text text-muted small"><i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}</span></li>
                            <li><span class="dropdown-item-text text-muted small"><i class="bi bi-shield-check me-1"></i>Role: <strong class="text-primary">{{ ucfirst(auth()->user()->role) }}</strong></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2 me-2"></i>Dashboard Utama</a></li>
                            <li><a class="dropdown-item small" href="{{ route('catalog.index') }}"><i class="bi bi-shop me-2"></i>Katalog Belanja</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger small">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item me-2">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary"><i class="bi bi-person-plus me-1"></i> Daftar Akun</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Sidebar (Only for Internal team: Owner, Admin, Staff) --}}
    @if(auth()->check() && auth()->user()->isInternal())
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="cs-sidebar" id="sidebar">
        <div class="px-3 pb-2 mb-2 border-bottom">
            <div class="p-2 bg-light rounded-3 d-flex align-items-center gap-2">
                <i class="bi bi-person-badge fs-4 text-primary"></i>
                <div class="small lh-sm">
                    <span class="d-block fw-bold text-dark">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1 py-0" style="font-size:0.65rem;">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <ul class="nav flex-column">
            <li class="sidebar-heading">Pusat Kendali</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill text-primary"></i> Dashboard & Laporan
                </a>
            </li>

            <li class="sidebar-heading">Master Data</li>
            @if(auth()->user()->isOwner())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="bi bi-tags text-warning"></i> Kategori Kamera
                    <span class="badge bg-danger-subtle text-danger ms-auto small" style="font-size:0.65rem;">Owner</span>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-camera text-info"></i> Produk Kamera
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                    <i class="bi bi-truck text-secondary"></i> Pemasok (Supplier)
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="bi bi-people text-primary"></i> Pelanggan (Customer)
                </a>
            </li>

            <li class="sidebar-heading">Operasional & Transaksi</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                    <i class="bi bi-box-arrow-in-down text-primary"></i> Stock In (Pembelian)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                    <i class="bi bi-cart-check text-success"></i> Penjualan (Sales)
                </a>
            </li>

            @if(auth()->user()->isOwner())
            <li class="sidebar-heading">Sistem & Otorisasi</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-shield-lock text-danger"></i> Kelola Pengguna
                    <span class="badge bg-danger-subtle text-danger ms-auto small" style="font-size:0.65rem;">Owner</span>
                </a>
            </li>
            @endif

            <li class="sidebar-heading">Toko Online</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('catalog.index') }}" target="_blank">
                    <i class="bi bi-shop text-dark"></i> Katalog Kamera Publik
                </a>
            </li>
        </ul>
    </nav>
    @endif

    {{-- Main Content --}}
    <main class="cs-main" style="margin-top:57px;">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="cs-footer">
        &copy; {{ date('Y') }} <strong>CameraStore</strong> — Inventory, Sales & Financial Management System. Web Programming 2.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');
        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
