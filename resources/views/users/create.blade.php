@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-person-plus-fill text-danger me-2"></i>Tambah Pengguna Baru</h4>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Rian Pratama" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Role Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select border-secondary-subtle-subtle @error('role') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Lihat Stok, Input Pembelian & Penjualan)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Sama seperti Staff: Input Pembelian & Penjualan)</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner (Bisa Semua: Kelola Produk, Supplier, User, dsb)</option>
                                <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan (Customer Pemesan Kamera)</option>
                            </select>
                            @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Email Login <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control border-secondary-subtle-subtle @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="user@camera.com" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control border-secondary-subtle-subtle @error('password') is-invalid @enderror" placeholder="Min. 6 karakter" required>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control border-secondary-subtle-subtle @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="081234567890">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Alamat</label>
                            <input type="text" name="address" class="form-control border-secondary-subtle-subtle @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Alamat domisili...">
                            @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger" style="background-color: var(--cs-accent); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
