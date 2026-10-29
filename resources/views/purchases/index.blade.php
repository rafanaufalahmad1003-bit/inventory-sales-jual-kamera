@extends('layouts.app')

@section('title', 'Transaksi Pembelian (Stock In)')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Transaksi Pembelian (Stock In)</h3>
        <p class="text-muted small mb-0">Pemasukan stok kamera dari distributor & pemasok resmi</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Input Pembelian Stok Baru
    </a>
</div>

<div class="card border-0 shadow-sm" >
    <div class="card-body p-3">
        <form method="GET" action="{{ route('purchases.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control border-secondary-subtle-subtle" placeholder="No. Pembelian atau Pemasok..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select border-secondary-subtle-subtle">
                    <option value="">-- Semua Status --</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control border-secondary-subtle-subtle" value="{{ request('from_date') }}" title="Dari Tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control border-secondary-subtle-subtle" value="{{ request('to_date') }}" title="Sampai Tanggal">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-secondary flex-grow-1"><i class="bi bi-funnel me-1"></i> Filter</button>
                @if(request('search') || request('status') || request('from_date') || request('to_date'))
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>No. Pembelian</th>
                        <th>Tanggal</th>
                        <th>Pemasok (Supplier)</th>
                        <th>Petugas Input</th>
                        <th class="text-end">Total Pembelian</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $index => $purchase)
                    <tr >
                        <td class="text-muted">{{ $purchases->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('purchases.show', $purchase) }}" class="fw-semibold text-info text-decoration-none font-monospace">
                                <i class="bi bi-receipt me-1"></i>{{ $purchase->purchase_number }}
                            </a>
                        </td>
                        <td class="text-muted small">
                            {{ $purchase->purchased_at ? $purchase->purchased_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $purchase->supplier->name ?? '-' }}</div>
                            <span class="text-muted small">{{ $purchase->purchaseItems->count() }} macam item</span>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary-subtle text-light">
                                <i class="bi bi-person me-1"></i>{{ $purchase->user->name ?? '-' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold font-monospace text-light">
                            {{ $purchase->formatted_total }}
                        </td>
                        <td class="text-center">
                            @if($purchase->status === 'completed')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail Transaksi">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada data transaksi pembelian stok kamera.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
