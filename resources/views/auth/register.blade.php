@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-6">
        <div class="card border-0 shadow-lg" >
            <div class="card-header bg-transparent text-center border-0 pt-4 pb-2">
                <div class="display-6 text-danger mb-2">📷</div>
                <h4 class="fw-bold text-dark mb-1">Registrasi Akun Pelanggan</h4>
                <p class="text-muted small">Buat akun untuk memesan kamera & aksesoris fotografi</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-light">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text border-secondary-subtle-subtle"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Reza Rahardian" required>
                        </div>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text border-secondary-subtle-subtle"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-secondary-subtle-subtle @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required>
                        </div>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Nomor WhatsApp / Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text border-secondary-subtle-subtle"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control border-secondary-subtle-subtle @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required>
                        </div>
                        @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Alamat Pengiriman</label>
                        <textarea name="address" rows="2" class="form-control border-secondary-subtle-subtle @error('address') is-invalid @enderror" placeholder="Alamat lengkap tujuan pengiriman kamera..." required>{{ old('address') }}</textarea>
                        @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary-subtle-subtle"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control border-secondary-subtle-subtle @error('password') is-invalid @enderror" placeholder="Min. 6 karakter" required>
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary-subtle-subtle"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-secondary-subtle-subtle" placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold mt-2" style="background-color: var(--cs-accent); border: none;">
                        <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
                    </button>

                    <div class="text-center mt-3 text-muted small">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-danger text-decoration-none">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
