<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CameraStore</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 1.5rem 0.5rem;
            color: #1e293b;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 460px;
            overflow: hidden;
        }
        .login-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 2rem 1.75rem 1.25rem;
            text-align: center;
        }
        .login-header .camera-icon {
            width: 58px; height: 58px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }
        .login-body { padding: 1.75rem; }
        .login-body .form-control {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            padding: 0.65rem 0.85rem 0.65rem 2.5rem;
            border-radius: 8px;
        }
        .login-body .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .login-body label { color: #334155; font-weight: 500; font-size: 0.88rem; }
        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .btn-login {
            background: #2563eb;
            border: none;
            color: #fff;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.15s ease;
        }
        .btn-login:hover {
            background: #1d4ed8;
            color: #fff;
        }
        .demo-badge {
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .demo-badge:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="camera-icon">
                <i class="bi bi-camera-fill"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">CameraStore</h4>
            <p class="text-muted small mb-0">Sistem Manajemen Bisnis & Inventori Kamera</p>
        </div>

        <div class="login-body">
            @if(session('success'))
            <div class="alert alert-success py-2 d-flex align-items-center small mb-3">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger py-2 d-flex align-items-center small mb-3">
                <i class="bi bi-exclamation-circle me-2 text-danger"></i>{{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Pengguna</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', 'owner@camera.com') }}"
                               placeholder="nama@camera.com" required autofocus>
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-lock"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" value="password"
                               placeholder="Password akun Anda" required>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="text-primary text-decoration-none small">
                        <i class="bi bi-shop me-1"></i>Buka Katalog Toko
                    </a>
                </div>

                <button type="submit" class="btn btn-login mb-3 shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Login Helpers -->
            <div class="p-3 rounded-3 border bg-light mt-2">
                <div class="text-secondary small fw-bold mb-2 text-uppercase" style="font-size:0.72rem; letter-spacing:0.5px;">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Klik Cepat Akun Demo (Password: password):
                </div>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge bg-danger demo-badge p-2 shadow-sm" onclick="fillLogin('owner@camera.com')">
                        <i class="bi bi-star-fill me-1"></i> Owner (Semua Akses)
                    </span>
                    <span class="badge bg-primary demo-badge p-2 shadow-sm" onclick="fillLogin('admin@camera.com')">
                        <i class="bi bi-shield me-1"></i> Admin (Stock In & Jual)
                    </span>
                    <span class="badge bg-info-subtle text-info-emphasis border border-info demo-badge p-2 shadow-sm" onclick="fillLogin('staff@camera.com')">
                        <i class="bi bi-person me-1"></i> Staff (Stock In & Jual)
                    </span>
                    <span class="badge bg-success demo-badge p-2 shadow-sm" onclick="fillLogin('pelanggan@camera.com')">
                        <i class="bi bi-bag me-1"></i> Pelanggan (Pesan Kamera)
                    </span>
                </div>
                <div class="text-muted" style="font-size:0.73rem;">
                    * <em>Admin & Staff</em>: memiliki izin sama (TIDAK BISA tambah/edit produk & supplier, hanya bisa Stock In & Penjualan). <em>Owner</em>: bisa semua data & batalkan transaksi.
                </div>
            </div>

            <div class="text-center mt-3 small text-muted">
                Belum punya akun pelanggan? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Daftar Akun Baru</a>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</body>
</html>
