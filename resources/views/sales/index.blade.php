@extends('layouts.app')

@section('title', 'Transaksi Penjualan Kamera')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-white mb-0"><i class="bi bi-cart-check me-2 text-success"></i>Transaksi Penjualan Kamera</h3>
        <p class="text-muted small mb-0">Kelola riwayat order pelanggan & transaksi penjualan kamera di toko</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-success">
        <i class="bi bi-cart-plus me-1"></i> Buat Penjualan Baru
    </a>
</div>

<div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('sales.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control bg-dark border-secondary text-light" placeholder="No. Penjualan atau Pelanggan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select bg-dark border-secondary text-light">
                    <option value="">-- Semua Status --</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control bg-dark border-secondary text-light" value="{{ request('from_date') }}" title="Dari Tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control bg-dark border-secondary text-light" value="{{ request('to_date') }}" title="Sampai Tanggal">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-secondary flex-grow-1"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request('search') || request('status') || request('from_date') || request('to_date'))
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
                <thead class="text-muted small text-uppercase" style="border-bottom: 2px solid var(--cs-border);">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Petugas / Kasir</th>
                        <th class="text-end">Total Penjualan</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $index => $sale)
                    <tr style="border-bottom: 1px solid var(--cs-border);">
                        <td class="text-muted">{{ $sales->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-info text-decoration-none font-monospace">
                                <i class="bi bi-receipt-cutoff me-1"></i>{{ $sale->sale_number }}
                            </a>
                        </td>
                        <td class="text-muted small">
                            {{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            @if($sale->customer)
                                <div class="fw-semibold text-white">
                                    <i class="bi bi-person me-1 text-info"></i>{{ $sale->customer->name }}
                                </div>
                                <span class="text-muted small">{{ $sale->customer->phone }}</span>
                            @else
                                <span class="text-muted">Pelanggan Umum (Walk-in)</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary text-light">
                                <i class="bi bi-person-badge me-1"></i>{{ $sale->user->name ?? '-' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold font-monospace text-success">
                            {{ $sale->formatted_total }}
                        </td>
                        <td class="text-center">
                            @if($sale->status === 'completed')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="Lihat Faktur">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada riwayat transaksi penjualan kamera.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
