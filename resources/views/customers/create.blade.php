@extends('layouts.app')

@section('title', 'Tambah Pelanggan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-person-plus text-danger me-2"></i>Tambah Data Pelanggan Baru</h4>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Nama Lengkap / Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Studio Foto Mentari / Dian Sastro" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control border-secondary-subtle-subtle @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control border-secondary-subtle-subtle @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="pelanggan@example.com">
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="form-control border-secondary-subtle-subtle @error('address') is-invalid @enderror" placeholder="Alamat domisili atau studio...">{{ old('address') }}</textarea>
                        @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger" style="background-color: var(--cs-accent); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Pelanggan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
