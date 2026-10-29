@extends('layouts.app')

@section('title', 'Faktur Penjualan - ' . $sale->sale_number)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold text-white mb-0">
            <i class="bi bi-receipt-cutoff text-success me-2"></i>Faktur Penjualan: {{ $sale->sale_number }}
        </h4>
        <span class="text-muted small">Transaksi Stock Out penjualan kamera kepada pelanggan</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        <button onclick="window.print()" class="btn btn-outline-light btn-sm"><i class="bi bi-printer me-1"></i> Cetak Faktur</button>
        @if(auth()->user()->isOwner() && $sale->status === 'completed')
        <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('PERINGATAN: Membatalkan penjualan ini akan mengembalikan (menambah kembali) stok kamera ke inventori. Lanjutkan?')">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i> Batalkan Penjualan (Owner)
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <!-- Header Details -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <h5 class="fw-bold text-white mb-3">Informasi Faktur</h5>

                <div class="mb-3">
                    <span class="text-muted small d-block">Status:</span>
                    @if($sale->status === 'completed')
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Selesai (Stok Berkurang)</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Dibatalkan (Stok Dipulihkan)</span>
                    @endif
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Pelanggan (Customer):</span>
                    @if($sale->customer)
                        <span class="text-light fw-semibold">{{ $sale->customer->name }}</span>
                        <div class="text-muted small">{{ $sale->customer->phone }}</div>
                        <div class="text-muted small">{{ $sale->customer->address }}</div>
                    @else
                        <span class="text-light">Pelanggan Umum (Walk-in)</span>
                    @endif
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Waktu Penjualan:</span>
                    <span class="text-light">{{ $sale->sold_at ? $sale->sold_at->format('d F Y, H:i') : '-' }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Kasir / Petugas:</span>
                    <span class="badge bg-dark border border-secondary-subtle text-info">
                        <i class="bi bi-person-badge me-1"></i>{{ $sale->user->name ?? '-' }} ({{ ucfirst($sale->user->role ?? '-') }})
                    </span>
                </div>

                <div class="mb-0">
                    <span class="text-muted small d-block">Catatan:</span>
                    <span class="text-light small">{{ $sale->notes ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" >
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="fw-bold text-white mb-0"><i class="bi bi-camera me-2 text-success"></i>Item Kamera & Aksesoris Terjual</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" >
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th>#</th>
                                <th>Produk Kamera</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleItems as $idx => $item)
                            <tr >
                                <td class="text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="fw-semibold text-white">{{ $item->product->name ?? '-' }}</div>
                                    <span class="badge bg-dark border border-secondary-subtle text-muted font-monospace small">{{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center fw-bold text-danger font-monospace">{{ $item->quantity }} unit</td>
                                <td class="text-end font-monospace">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold font-monospace text-success">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold text-white fs-6">TOTAL PEMBAYARAN:</td>
                                <td class="text-end fw-bold text-success font-monospace fs-5">{{ $sale->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
