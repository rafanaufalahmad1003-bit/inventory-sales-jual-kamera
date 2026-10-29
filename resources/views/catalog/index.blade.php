@extends('layouts.app')

@section('title', 'Katalog Kamera Online')

@section('content')
<!-- Hero Banner -->
<div class="p-4 p-md-5 mb-4 rounded-3 text-white shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white !important;" class="text-white">
    <div class="col-md-8 position-relative" style="z-index: 2;">
        <span class="badge bg-danger px-3 py-2 mb-2"><i class="bi bi-camera-fill me-1"></i> Toko Kamera & Aksesoris Fotografi</span>
        <h1 class="display-5 fw-bold mb-2">Beli Kamera Impian Anda</h1>
        <p class="lead text-light mb-4">Koleksi terlengkap kamera Mirrorless, DSLR, Lensa, Action Cam & aksesoris bergaransi resmi.</p>
        <div class="d-flex flex-wrap gap-2">
            @auth
                @if(auth()->user()->isPelanggan())
                <a href="{{ route('catalog.myOrders') }}" class="btn btn-outline-light"><i class="bi bi-bag-check me-1"></i> Riwayat Pesanan Saya</a>
                @elseif(auth()->user()->isInternal())
                <a href="{{ route('dashboard') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;"><i class="bi bi-speedometer2 me-1"></i> Kembali ke Dashboard Admin</a>
                @endif
            @else
            <a href="{{ route('register') }}" class="btn btn-danger px-4" style="background-color: var(--cs-accent); border:none;"><i class="bi bi-person-plus me-1"></i> Daftar Akun Pelanggan</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
            @endauth
        </div>
    </div>
    <div class="position-absolute end-0 top-0 bottom-0 d-none d-lg-flex align-items-center opacity-25 pe-5">
        <i class="bi bi-camera fs-1" style="font-size: 14rem !important;"></i>
    </div>
</div>

<!-- Search & Filter -->
<div class="card border-0 shadow-sm mb-4" >
    <div class="card-body p-3">
        <form method="GET" action="{{ route('catalog.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-secondary-subtle-subtle" placeholder="Cari nama kamera, tipe, merk..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select border-secondary-subtle-subtle">
                    <option value="">-- Semua Kategori Kamera --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-danger flex-grow-1" style="background-color: var(--cs-accent); border:none;">
                    <i class="bi bi-funnel me-1"></i> Cari Kamera
                </button>
                @if(request('search') || request('category_id'))
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Product Cards Grid -->
<div class="row g-4 mb-4">
    @forelse($products as $product)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm transition-hover" style=" border: 1px solid var(--cs-border) !important; transition: transform 0.2s;">
            <!-- Product Image -->
            <div class="position-relative overflow-hidden bg-dark text-center" style="height: 220px;">
                @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                @else
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                    <i class="bi bi-camera fs-1"></i>
                </div>
                @endif

                <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-light border border-secondary-subtle">
                    {{ $product->category->name ?? 'Kamera' }}
                </span>

                @if($product->stock <= 0)
                <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Habis</span>
                @elseif($product->isLowStock())
                <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">Sisa {{ $product->stock }} unit</span>
                @else
                <span class="position-absolute top-0 end-0 m-2 badge bg-success">Tersedia {{ $product->stock }} unit</span>
                @endif
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column p-3">
                <div class="small text-muted font-monospace mb-1">{{ $product->sku }}</div>
                <h5 class="card-title fw-bold text-white mb-2 line-clamp-2" style="font-size: 1.1rem;">
                    {{ $product->name }}
                </h5>
                <p class="card-text text-muted small flex-grow-1 line-clamp-3">
                    {{ Str::limit($product->description ?? 'Kamera kualitas tinggi dengan garansi distributor resmi.', 90) }}
                </p>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: var(--cs-border) !important;">
                    <div>
                        <div class="text-muted small">Harga Spesial:</div>
                        <div class="fw-bold text-success fs-5 font-monospace">{{ $product->formatted_selling_price }}</div>
                    </div>
                    <div>
                        <a href="{{ route('catalog.show', $product) }}" class="btn btn-outline-info btn-sm me-1" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('catalog.order', $product) }}" class="btn btn-danger btn-sm" style="background-color: var(--cs-accent); border:none;">
                            <i class="bi bi-cart-plus me-1"></i> Pesan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-camera-slash display-4 text-muted d-block mb-3"></i>
        <h4 class="text-white">Tidak ada kamera yang ditemukan</h4>
        <p class="text-muted">Coba kata kunci pencarian lain atau pilih kategori kamera lainnya.</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-danger">Reset Pencarian</a>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection
