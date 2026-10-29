@extends('layouts.app')

@section('title', 'Detail Pembelian - ' . $purchase->purchase_number)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold text-white mb-0">
            <i class="bi bi-receipt text-primary me-2"></i>Faktur Pembelian: {{ $purchase->purchase_number }}
        </h4>
        <span class="text-muted small">Transaksi Stock In dari pemasok kamera</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        <button onclick="window.print()" class="btn btn-outline-light btn-sm"><i class="bi bi-printer me-1"></i> Cetak</button>
        @if(auth()->user()->isOwner() && $purchase->status === 'completed')
        <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('PERINGATAN: Membatalkan transaksi ini akan mengembalikan (mengurangi) stok produk yang telah ditambahkan. Lanjutkan?')">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi (Owner)
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
                <h5 class="fw-bold text-white mb-3">Informasi Transaksi</h5>

                <div class="mb-3">
                    <span class="text-muted small d-block">Status:</span>
                    @if($purchase->status === 'completed')
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Selesai (Stok Masuk)</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Dibatalkan (Stok Dikurangi)</span>
                    @endif
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Pemasok (Supplier):</span>
                    <span class="text-light fw-semibold">{{ $purchase->supplier->name ?? '-' }}</span>
                    <div class="text-muted small">{{ $purchase->supplier->phone ?? '' }}</div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Tanggal Transaksi:</span>
                    <span class="text-light">{{ $purchase->purchased_at ? $purchase->purchased_at->format('d F Y, H:i') : '-' }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Petugas Yang Menginput:</span>
                    <span class="badge bg-dark border border-secondary-subtle text-info">
                        <i class="bi bi-person-badge me-1"></i>{{ $purchase->user->name ?? '-' }} ({{ ucfirst($purchase->user->role ?? '-') }})
                    </span>
                </div>

                <div class="mb-0">
                    <span class="text-muted small d-block">Catatan:</span>
                    <span class="text-light small">{{ $purchase->notes ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" >
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="fw-bold text-white mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Rincian Produk Kamera Yang Diterima</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" >
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th>#</th>
                                <th>Produk Kamera</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Beli Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->purchaseItems as $idx => $item)
                            <tr >
                                <td class="text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="fw-semibold text-white">{{ $item->product->name ?? '-' }}</div>
                                    <span class="badge bg-dark border border-secondary-subtle text-muted font-monospace small">{{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center fw-bold text-primary font-monospace">+{{ $item->quantity }}</td>
                                <td class="text-end font-monospace">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold font-monospace text-light">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold text-white fs-6">TOTAL PEMBELIAN:</td>
                                <td class="text-end fw-bold text-success font-monospace fs-5">{{ $purchase->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
