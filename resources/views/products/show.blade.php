@extends('layouts.app')

@section('title', 'Detail Produk Kamera - ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold text-white mb-0"><i class="bi bi-camera me-2 text-danger"></i>{{ $product->name }}</h4>
        <span class="badge bg-dark border border-secondary-subtle text-info font-monospace">{{ $product->sku }}</span>
        <span class="badge bg-secondary ms-1">{{ $product->category->name ?? '-' }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        @if(auth()->user()->isOwner())
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" >
            @if($product->image)
            <img src="{{ $product->image }}" class="card-img-top p-2 rounded" alt="{{ $product->name }}" style="max-height: 240px; object-fit: cover;">
            @else
            <div class="d-flex align-items-center justify-content-center bg-dark text-muted rounded-top" style="height: 200px;">
                <i class="bi bi-camera fs-1"></i>
            </div>
            @endif
            <div class="card-body">
                <h5 class="fw-bold text-white mb-2">{{ $product->name }}</h5>
                <p class="text-muted small mb-3">{{ $product->description ?? 'Tidak ada deskripsi spesifikasi.' }}</p>

                <div class="list-group list-group-flush bg-transparent">
                    <div class="list-group-item bg-transparent text-light d-flex justify-content-between px-0" style="border-color: var(--cs-border);">
                        <span class="text-muted">Kategori:</span>
                        <span class="fw-semibold">{{ $product->category->name ?? '-' }}</span>
                    </div>
                    <div class="list-group-item bg-transparent text-light d-flex justify-content-between px-0" style="border-color: var(--cs-border);">
                        <span class="text-muted">Harga Beli:</span>
                        <span class="fw-semibold font-monospace">{{ $product->formatted_purchase_price }}</span>
                    </div>
                    <div class="list-group-item bg-transparent text-light d-flex justify-content-between px-0" style="border-color: var(--cs-border);">
                        <span class="text-muted">Harga Jual:</span>
                        <span class="fw-bold text-success font-monospace">{{ $product->formatted_selling_price }}</span>
                    </div>
                    <div class="list-group-item bg-transparent text-light d-flex justify-content-between px-0" style="border-color: var(--cs-border);">
                        <span class="text-muted">Stok Tersedia:</span>
                        @if($product->stock <= 0)
                            <span class="badge bg-danger">Habis (0)</span>
                        @elseif($product->isLowStock())
                            <span class="badge bg-warning text-dark">{{ $product->stock }} unit (Menipis)</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock }} unit</span>
                        @endif
                    </div>
                    <div class="list-group-item bg-transparent text-light d-flex justify-content-between px-0" style="border-color: var(--cs-border);">
                        <span class="text-muted">Minimum Stok:</span>
                        <span class="font-monospace text-muted">{{ $product->minimum_stock }} unit</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm" >
            <div class="card-header bg-transparent border-0 pt-3">
                <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-light" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                            <i class="bi bi-cart-check me-1 text-success"></i> Riwayat Penjualan ({{ $product->saleItems->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-light" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab">
                            <i class="bi bi-box-arrow-in-down me-1 text-primary"></i> Riwayat Pembelian Masuk ({{ $product->purchaseItems->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-3">
                <div class="tab-content" id="productTabsContent">
                    <!-- Sales Tab -->
                    <div class="tab-pane fade show active" id="sales" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small" >
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>No. Penjualan</th>
                                        <th>Tanggal</th>
                                        <th>Pelanggan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga Jual</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->saleItems as $sItem)
                                    <tr>
                                        <td>
                                            <a href="{{ route('sales.show', $sItem->sale) }}" class="text-info text-decoration-none font-monospace">
                                                {{ $sItem->sale->sale_number }}
                                            </a>
                                        </td>
                                        <td class="text-muted">{{ $sItem->sale->sold_at ? $sItem->sale->sold_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{{ $sItem->sale->customer->name ?? 'Pelanggan Umum' }}</td>
                                        <td class="text-center fw-semibold text-danger">-{{ $sItem->quantity }}</td>
                                        <td class="text-end font-monospace">Rp {{ number_format($sItem->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold text-success font-monospace">Rp {{ number_format($sItem->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">Belum ada transaksi penjualan untuk produk ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Purchases Tab -->
                    <div class="tab-pane fade" id="purchases" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small" >
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>No. Pembelian</th>
                                        <th>Tanggal</th>
                                        <th>Pemasok (Supplier)</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga Beli</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product->purchaseItems as $pItem)
                                    <tr>
                                        <td>
                                            <a href="{{ route('purchases.show', $pItem->purchase) }}" class="text-info text-decoration-none font-monospace">
                                                {{ $pItem->purchase->purchase_number }}
                                            </a>
                                        </td>
                                        <td class="text-muted">{{ $pItem->purchase->purchased_at ? $pItem->purchase->purchased_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{{ $pItem->purchase->supplier->name ?? '-' }}</td>
                                        <td class="text-center fw-semibold text-success">+{{ $pItem->quantity }}</td>
                                        <td class="text-end font-monospace">Rp {{ number_format($pItem->unit_cost, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold font-monospace">Rp {{ number_format($pItem->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">Belum ada riwayat stock-in/pembelian untuk produk ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
