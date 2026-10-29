@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Pengguna: {{ $user->name }}</h4>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
            <div class="card-body p-4">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-dark border-secondary text-light @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Role Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select bg-dark border-secondary text-light @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff (Lihat Stok, Input Pembelian & Penjualan)</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Sama seperti Staff: Input Pembelian & Penjualan)</option>
                                <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner (Bisa Semua: Kelola Produk, Supplier, User, dsb)</option>
                                <option value="pelanggan" {{ old('role', $user->role) == 'pelanggan' ? 'selected' : '' }}>Pelanggan (Customer Pemesan Kamera)</option>
                            </select>
                            @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Email Login <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-light @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Ganti Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control bg-dark border-secondary text-light @error('password') is-invalid @enderror" placeholder="Isi untuk ganti password">
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control bg-dark border-secondary text-light @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Alamat</label>
                            <input type="text" name="address" class="form-control bg-dark border-secondary text-light @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                            @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check2-circle me-1"></i> Perbarui Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
