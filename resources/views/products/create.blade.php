@extends('layouts.app')

@section('title', 'Tambah Produk Kamera Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-camera-fill text-danger me-2"></i>Tambah Produk Kamera Baru</h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control border-secondary-subtle-subtle font-monospace @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Contoh: CAM-MIR-004" required>
                            <div class="form-text text-muted small">Harus unik untuk setiap produk kamera.</div>
                            @error('sku') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Kategori Kamera <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select border-secondary-subtle-subtle @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Nama Produk Kamera <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Canon EOS R5 Body" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted">Rp</span>
                                <input type="number" step="1000" name="purchase_price" class="form-control border-secondary-subtle-subtle @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" placeholder="0" required>
                            </div>
                            @error('purchase_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted">Rp</span>
                                <input type="number" step="1000" name="selling_price" class="form-control border-secondary-subtle-subtle @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}" placeholder="0" required>
                            </div>
                            @error('selling_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control border-secondary-subtle-subtle @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_stock" class="form-control border-secondary-subtle-subtle @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock', 2) }}" min="0" required>
                            <div class="form-text text-muted small">Peringatan otomatis muncul di dashboard jika stok berada pada atau di bawah angka ini.</div>
                            @error('minimum_stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">URL Foto Kamera (Opsional)</label>
                        <input type="url" name="image" class="form-control border-secondary-subtle-subtle @error('image') is-invalid @enderror" value="{{ old('image') }}" placeholder="https://example.com/foto-kamera.jpg">
                        <div class="form-text text-muted small">Dapat menggunakan URL gambar dari Unsplash atau sumber lain.</div>
                        @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold">Spesifikasi & Deskripsi Kamera</label>
                        <textarea name="description" rows="3" class="form-control border-secondary-subtle-subtle @error('description') is-invalid @enderror" placeholder="Sensor, resolusi, fitur utama, isi kelengkapan box...">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger" style="background-color: var(--cs-accent); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Produk Kamera
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
