@extends('layouts.app')

@section('title', $product->name . ' - Detail Kamera')

@section('content')
<div class="mb-3">
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Kamera
    </a>
</div>

<div class="card border-0 shadow-sm mb-4" >
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="bg-dark rounded p-3 text-center position-relative">
                    @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 380px; object-fit: contain;">
                    @else
                    <div class="py-5 text-muted">
                        <i class="bi bi-camera display-1"></i>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-md-7 d-flex flex-column">
                <div class="mb-2">
                    <span class="badge bg-secondary me-1">{{ $product->category->name ?? 'Kamera' }}</span>
                    <span class="badge bg-dark border border-secondary-subtle text-info font-monospace">{{ $product->sku }}</span>
                </div>

                <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>

                <div class="mb-3">
                    <span class="text-muted small">Harga Penjualan:</span>
                    <div class="display-6 fw-bold text-success font-monospace mb-1">{{ $product->formatted_selling_price }}</div>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success"><i class="bi bi-shield-check me-1"></i> Garansi Resmi Distributor</span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Status Ketersediaan Stok:</span>
                    @if($product->stock <= 0)
                        <span class="badge bg-danger fs-6">Stok Habis</span>
                    @elseif($product->isLowStock())
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-exclamation-triangle-fill me-1"></i>Tersisa {{ $product->stock }} unit lagi (Segera Pesan!)</span>
                    @else
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Ready Stock (Tersedia {{ $product->stock }} unit)</span>
                    @endif
                </div>

                <div class="mb-4 flex-grow-1">
                    <h6 class="fw-bold text-light mb-2">Spesifikasi & Deskripsi Produk:</h6>
                    <p class="text-light text-opacity-75 lead fs-6" style="white-space: pre-line;">
                        {{ $product->description ?? 'Kamera berkualitas tinggi dengan sensor profesional, lensa tajam, dan fitur perekaman mutakhir untuk segala kebutuhan fotografi dan videografi Anda.' }}
                    </p>
                </div>

                <div class="d-flex gap-2 pt-3 border-top" style="border-color: var(--cs-border) !important;">
                    @if($product->stock > 0)
                    <a href="{{ route('catalog.order', $product) }}" class="btn btn-danger btn-lg px-4 flex-grow-1" style="background-color: var(--cs-accent); border:none;">
                        <i class="bi bi-cart-check-fill me-2"></i> Pesan Kamera Ini Sekarang
                    </a>
                    @else
                    <button class="btn btn-secondary btn-lg flex-grow-1" disabled>
                        <i class="bi bi-x-circle me-1"></i> Stok Sedang Habis
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($relatedProducts->count() > 0)
<h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid me-2 text-danger"></i>Kamera Terkait Lainnya</h5>
<div class="row g-3">
    @foreach($relatedProducts as $rel)
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm" >
            <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-1">{{ $rel->name }}</h6>
                <div class="text-success fw-bold font-monospace small mb-2">{{ $rel->formatted_selling_price }}</div>
                <a href="{{ route('catalog.show', $rel) }}" class="btn btn-sm btn-outline-info w-100">Lihat Kamera</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
