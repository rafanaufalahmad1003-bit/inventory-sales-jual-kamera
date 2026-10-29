@extends('layouts.app')

@section('title', 'Detail Pelanggan - ' . $customer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold text-white mb-0"><i class="bi bi-person-circle me-2 text-info"></i>{{ $customer->name }}</h4>
        <span class="text-muted small">Profil pelanggan dan rekam jejak pesanan kamera</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
            <div class="card-body p-4">
                <h5 class="fw-bold text-white mb-3">Informasi Pelanggan</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Nama Lengkap:</span>
                    <span class="text-light fw-semibold">{{ $customer->name }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Nomor Telepon:</span>
                    <a href="tel:{{ $customer->phone }}" class="text-info font-monospace">{{ $customer->phone }}</a>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Email:</span>
                    <span class="text-light">{{ $customer->email ?? '-' }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Alamat Pengiriman:</span>
                    <span class="text-light small">{{ $customer->address ?? '-' }}</span>
                </div>
                <div class="pt-3 border-top" style="border-color: var(--cs-border) !important;">
                    <a href="{{ route('sales.create') }}?customer_id={{ $customer->id }}" class="btn btn-success w-100 btn-sm">
                        <i class="bi bi-cart-plus me-1"></i> Buat Penjualan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="fw-bold text-white mb-0"><i class="bi bi-bag-check me-2 text-success"></i>Riwayat Transaksi Pembelian ({{ $customer->sales->count() }})</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 small" style="background: transparent;">
                        <thead class="text-muted">
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Barang Dibeli</th>
                                <th class="text-end">Total Pembayaran</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->sales as $sale)
                            <tr>
                                <td>
                                    <span class="text-info font-monospace">{{ $sale->sale_number }}</span>
                                </td>
                                <td class="text-muted">{{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($sale->saleItems as $sItem)
                                        <li>{{ $sItem->product->name ?? '-' }} <span class="badge bg-secondary font-monospace">{{ $sItem->quantity }}x</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-end fw-semibold text-success font-monospace">{{ $sale->formatted_total }}</td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">Belum ada riwayat transaksi belanja untuk pelanggan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
