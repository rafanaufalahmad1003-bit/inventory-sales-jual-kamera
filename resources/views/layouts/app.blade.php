<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CameraStore') - Inventory & Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --cs-dark: #1a1a2e;
            --cs-dark2: #16213e;
            --cs-accent: #e94560;
            --cs-accent-hover: #c73e54;
            --cs-sidebar-width: 250px;
            --cs-body-bg: #0f0f1a;
            --cs-card-bg: #1e1e30;
            --cs-text: #e0e0e0;
            --cs-text-muted: #8a8a9a;
            --cs-border: #2a2a40;
        }
        body {
            background-color: var(--cs-body-bg);
            color: var(--cs-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        /* Navbar */
        .cs-navbar {
            background: linear-gradient(135deg, var(--cs-dark) 0%, var(--cs-dark2) 100%) !important;
            border-bottom: 2px solid var(--cs-accent);
            padding: 0.6rem 1.2rem;
            z-index: 1040;
        }
        .cs-navbar .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
        }
        .cs-navbar .navbar-brand:hover { color: var(--cs-accent) !important; }
        .cs-navbar .nav-link { color: #ccc !important; }
        .cs-navbar .nav-link:hover { color: #fff !important; }
        .cs-navbar .dropdown-menu {
            background: var(--cs-dark2);
            border: 1px solid var(--cs-border);
        }
        .cs-navbar .dropdown-item {
            color: var(--cs-text);
        }
        .cs-navbar .dropdown-item:hover {
            background: var(--cs-accent);
            color: #fff;
        }

        /* Sidebar for internal staff/owner */
        .cs-sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            width: var(--cs-sidebar-width);
            height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--cs-dark) 0%, var(--cs-dark2) 100%);
            border-right: 1px solid var(--cs-border);
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1030;
            padding-top: 0.5rem;
        }
        .cs-sidebar .nav-link {
            color: var(--cs-text-muted);
            padding: 0.65rem 1.25rem;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .cs-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(233, 69, 96, 0.1);
            border-left-color: var(--cs-accent);
        }
        .cs-sidebar .nav-link.active {
            color: #fff;
            background: rgba(233, 69, 96, 0.15);
            border-left-color: var(--cs-accent);
            font-weight: 600;
        }
        .cs-sidebar .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; }
        .cs-sidebar .sidebar-heading {
            color: var(--cs-text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 1rem 1.25rem 0.4rem;
            font-weight: 600;
        }

        /* Main content */
        .cs-main {
            padding: 1.5rem 2rem 3rem;
            min-height: calc(100vh - 56px);
        }
        .has-sidebar .cs-main {
            margin-left: var(--cs-sidebar-width);
        }

        /* Footer */
        .cs-footer {
            background: var(--cs-dark);
            border-top: 1px solid var(--cs-border);
            padding: 1rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--cs-text-muted);
        }
        .has-sidebar .cs-footer {
            margin-left: var(--cs-sidebar-width);
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1025;
        }

        @media (max-width: 991.98px) {
            .cs-sidebar {
                transform: translateX(-100%);
            }
            .cs-sidebar.show {
                transform: translateX(0);
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
    <nav class="navbar navbar-expand-lg navbar-dark cs-navbar fixed-top">
        <div class="container-fluid">
            @if(auth()->check() && auth()->user()->isInternal())
            <button class="btn btn-outline-light btn-sm d-lg-none me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list fs-5"></i>
            </button>
            @endif

            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('catalog.index') }}">
                <i class="bi bi-camera-fill text-danger fs-4"></i>
                <span class="fw-bold">CameraStore</span>
                <span class="badge bg-danger ms-1 small" style="font-size:0.65rem;">PRO</span>
            </a>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalog.*') ? 'text-white fw-bold' : '' }}" href="{{ route('catalog.index') }}">
                            <i class="bi bi-shop me-1 text-danger"></i> Katalog Kamera
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isInternal())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'text-white fw-bold' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1 text-info"></i> Dashboard Toko
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('catalog.myOrders') ? 'text-white fw-bold' : '' }}" href="{{ route('catalog.myOrders') }}">
                                <i class="bi bi-bag-check me-1 text-success"></i> Pesanan Saya
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-dark border border-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                                <i class="bi bi-person-fill text-light"></i>
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <span class="{{ auth()->user()->getRoleBadgeClass() }} ms-1 small">{{ strtoupper(auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><span class="dropdown-item-text text-muted small"><i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}</span></li>
                            <li><span class="dropdown-item-text text-muted small"><i class="bi bi-shield me-1"></i>Role: {{ ucfirst(auth()->user()->role) }}</span></li>
                            <li><hr class="dropdown-divider" style="border-color:var(--cs-border)"></li>
                            @if(auth()->user()->isInternal())
                            <li><a class="dropdown-item small" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            @else
                            <li><a class="dropdown-item small" href="{{ route('catalog.myOrders') }}"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</a></li>
                            @endif
                            <li><a class="dropdown-item small" href="{{ route('catalog.index') }}"><i class="bi bi-shop me-2"></i>Katalog Belanja</a></li>
                            <li><hr class="dropdown-divider" style="border-color:var(--cs-border)"></li>
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
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-sm btn-danger" style="background-color:var(--cs-accent); border:none;"><i class="bi bi-person-plus me-1"></i> Daftar Akun</a>
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
        <ul class="nav flex-column">
            <li class="sidebar-heading">Menu Utama</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 text-danger"></i> Dashboard
                </a>
            </li>

            <li class="sidebar-heading">Master Data</li>
            @if(auth()->user()->isOwner())
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="bi bi-tags text-warning"></i> Kategori Kamera <span class="badge bg-danger ms-auto small" style="font-size:0.6rem;">Owner</span>
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
                    <i class="bi bi-shield-lock text-danger"></i> Kelola Pengguna <span class="badge bg-danger ms-auto small" style="font-size:0.6rem;">Owner</span>
                </a>
            </li>
            @endif

            <li class="sidebar-heading">Toko Online</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('catalog.index') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right text-light"></i> Lihat Katalog Publik
                </a>
            </li>
        </ul>
    </nav>
    @endif

    {{-- Main Content --}}
    <main class="cs-main" style="margin-top:56px;">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="background:rgba(25,135,84,0.18);border:1px solid #198754;color:#75e6a0;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="background:rgba(220,53,69,0.18);border:1px solid #dc3545;color:#f5a3ad;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="cs-footer">
        &copy; {{ date('Y') }} <strong>CameraStore</strong> — Inventory & Sales Management System. Web Programming 2 UTS.
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
