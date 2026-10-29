@extends('layouts.app')

@section('title', 'Katalog Produk Kamera')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-camera me-2 text-danger"></i>Master Produk Kamera</h3>
        <p class="text-muted small mb-0">Daftar inventori kamera, lensa, dan aksesoris fotografi</p>
    </div>
    @if(auth()->user()->isOwner())
    <a href="{{ route('products.create') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Kamera
    </a>
    @else
    <div class="badge bg-secondary px-3 py-2">
        <i class="bi bi-shield-lock me-1"></i> Mode {{ ucfirst(auth()->user()->role) }}: Hanya Owner yang dapat menambah/mengedit produk
    </div>
    @endif
</div>

@if(!auth()->user()->isOwner())
<div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: rgba(13, 202, 240, 0.15); color: #0dcaf0;">
    <i class="bi bi-info-circle-fill me-1"></i> <strong>Informasi Otorisasi:</strong> Sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>, Anda memiliki izin untuk melihat stok kamera serta membuat transaksi <strong>Stock In (Pembelian)</strong> dan <strong>Penjualan</strong>. Pengelolaan master data produk hanya dapat dilakukan oleh Owner.
</div>
@endif

<div class="card border-0 shadow-sm" >
    <div class="card-body p-3">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-secondary-subtle-subtle" placeholder="Cari nama, SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select border-secondary-subtle-subtle">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="stock_status" class="form-select border-secondary-subtle-subtle">
                    <option value="">-- Status Stok --</option>
                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Stok Tersedia (>0)</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (<= Min)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-secondary flex-grow-1"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request('search') || request('category_id') || request('stock_status'))
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Info Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-end" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr >
                        <td class="text-muted">{{ $products->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $product->name }}</div>
                            <span class="badge bg-dark border border-secondary-subtle text-info font-monospace small">{{ $product->sku }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-end text-muted font-monospace">
                            {{ $product->formatted_purchase_price }}
                        </td>
                        <td class="text-end fw-semibold text-success font-monospace">
                            {{ $product->formatted_selling_price }}
                        </td>
                        <td class="text-center">
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">Habis (0)</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $product->stock }} unit</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }} unit</span>
                            @endif
                        </td>
                        <td class="text-center text-muted font-monospace">{{ $product->minimum_stock }}</td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat Detail & Riwayat">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isOwner())
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Produk">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mengarsipkan produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-camera-slash fs-2 d-block mb-2"></i>
                            Tidak ada produk kamera yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
