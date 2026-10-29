@extends('layouts.app')

@section('title', 'Edit Produk Kamera')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Produk: {{ $product->name }}</h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
            <div class="card-body p-4">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control bg-dark border-secondary text-light font-monospace @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Kategori Kamera <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select bg-dark border-secondary text-light @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Nama Produk Kamera <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-dark border-secondary text-light @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted">Rp</span>
                                <input type="number" step="1000" name="purchase_price" class="form-control bg-dark border-secondary text-light @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', (int)$product->purchase_price) }}" required>
                            </div>
                            @error('purchase_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted">Rp</span>
                                <input type="number" step="1000" name="selling_price" class="form-control bg-dark border-secondary text-light @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', (int)$product->selling_price) }}" required>
                            </div>
                            @error('selling_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control bg-dark border-secondary text-light @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
                            <div class="form-text text-muted small">Untuk penambahan stok operasional rutin, disarankan melalui transaksi <strong>Stock In (Pembelian)</strong>.</div>
                            @error('stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_stock" class="form-control bg-dark border-secondary text-light @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" required>
                            @error('minimum_stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">URL Foto Kamera</label>
                        <input type="url" name="image" class="form-control bg-dark border-secondary text-light @error('image') is-invalid @enderror" value="{{ old('image', $product->image) }}">
                        @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold">Spesifikasi & Deskripsi Kamera</label>
                        <textarea name="description" rows="3" class="form-control bg-dark border-secondary text-light @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check2-circle me-1"></i> Perbarui Data Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
