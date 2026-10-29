<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CameraStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1.5rem 0.5rem;
        }
        .login-card {
            background: #1e1e30;
            border: 1px solid #2a2a40;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #e94560 0%, #c73e54 100%);
            padding: 1.75rem 1.5rem;
            text-align: center;
            color: #fff;
        }
        .login-header .camera-icon {
            width: 65px; height: 65px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .login-body { padding: 1.75rem; }
        .login-body .form-control {
            background: #12121f;
            border: 1px solid #2a2a40;
            color: #e0e0e0;
            padding: 0.65rem 0.85rem 0.65rem 2.5rem;
        }
        .login-body .form-control:focus {
            border-color: #e94560;
            box-shadow: 0 0 0 0.2rem rgba(233,69,96,0.25);
            background: #15152a;
            color: #fff;
        }
        .login-body .form-control::placeholder { color: #6a6a80; }
        .login-body label { color: #bbb; font-weight: 500; font-size: 0.88rem; }
        .input-icon-wrapper { position: relative; }
        .input-icon-wrapper i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6a6a80;
        }
        .btn-login {
            background: #e94560;
            border: none;
            color: #fff;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: #c73e54;
            color: #fff;
        }
        .demo-badge {
            cursor: pointer;
            transition: transform 0.15s, opacity 0.15s;
        }
        .demo-badge:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="camera-icon">
                <i class="bi bi-camera-fill"></i>
            </div>
            <h3 class="fw-bold mb-0">CameraStore</h3>
            <p class="small opacity-75 mb-0">Inventory & Sales Management System</p>
        </div>

        <div class="login-body">
            @if(session('success'))
            <div class="alert alert-success py-2 d-flex align-items-center small" style="background:rgba(25,135,84,0.18);border:1px solid #198754;color:#75e6a0;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger py-2 d-flex align-items-center small" style="background:rgba(220,53,69,0.18);border:1px solid #dc3545;color:#f5a3ad;">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
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
                        <input type="checkbox" class="form-check-input bg-dark border-secondary" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small" for="remember">Ingat Saya</label>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="text-info text-decoration-none small">
                        <i class="bi bi-shop me-1"></i>Katalog Toko
                    </a>
                </div>

                <button type="submit" class="btn btn-login mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Login Helpers -->
            <div class="p-3 rounded border border-secondary bg-dark bg-opacity-50 mt-2">
                <div class="text-muted small fw-bold mb-2 text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Klik Akun Demo (Password: password):
                </div>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge bg-danger demo-badge p-2" onclick="fillLogin('owner@camera.com')">
                        <i class="bi bi-star-fill me-1"></i> Owner (Bisa Semua)
                    </span>
                    <span class="badge bg-primary demo-badge p-2" onclick="fillLogin('admin@camera.com')">
                        <i class="bi bi-shield me-1"></i> Admin (Stock In & Jual)
                    </span>
                    <span class="badge bg-info text-dark demo-badge p-2" onclick="fillLogin('staff@camera.com')">
                        <i class="bi bi-person me-1"></i> Staff (Stock In & Jual)
                    </span>
                    <span class="badge bg-success demo-badge p-2" onclick="fillLogin('pelanggan@camera.com')">
                        <i class="bi bi-bag me-1"></i> Pelanggan (Pesan Kamera)
                    </span>
                </div>
                <div class="text-muted" style="font-size:0.72rem;">
                    * <em>Admin & Staff</em>: memiliki izin sama (TIDAK BISA tambah/edit produk & supplier, hanya bisa Stock In & Penjualan). <em>Owner</em>: bisa semua data & batalkan transaksi.
                </div>
            </div>

            <div class="text-center mt-3 small text-muted">
                Belum punya akun pelanggan? <a href="{{ route('register') }}" class="text-danger text-decoration-none fw-semibold">Daftar Akun Baru</a>
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
