@extends('layouts.app')

@section('title', 'Edit Pemasok Kamera')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Pemasok: {{ $supplier->name }}</h4>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Nama Perusahaan / Pemasok <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nama Kontak (PIC)</label>
                            <input type="text" name="contact_person" class="form-control border-secondary-subtle-subtle @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $supplier->contact_person) }}">
                            @error('contact_person') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control border-secondary-subtle-subtle @error('phone') is-invalid @enderror" value="{{ old('phone', $supplier->phone) }}" required>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control border-secondary-subtle-subtle @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email) }}">
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold">Alamat Kantor / Gudang Pemasok</label>
                        <textarea name="address" rows="3" class="form-control border-secondary-subtle-subtle @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
                        @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check2-circle me-1"></i> Perbarui Pemasok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
