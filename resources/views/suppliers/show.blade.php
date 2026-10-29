@extends('layouts.app')

@section('title', 'Detail Pemasok - ' . $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-truck me-2 text-danger"></i>{{ $supplier->name }}</h4>
        <span class="text-muted small">Detail data pemasok kamera & riwayat pengadaan</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        @if(auth()->user()->isOwner())
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3">Informasi Kontak</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Nama Perusahaan:</span>
                    <span class="text-light fw-semibold">{{ $supplier->name }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Contact Person:</span>
                    <span class="text-light">{{ $supplier->contact_person ?? '-' }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Telepon:</span>
                    <a href="tel:{{ $supplier->phone }}" class="text-info font-monospace">{{ $supplier->phone }}</a>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Email:</span>
                    <span class="text-light">{{ $supplier->email ?? '-' }}</span>
                </div>
                <div class="mb-0">
                    <span class="text-muted small d-block">Alamat:</span>
                    <span class="text-light small">{{ $supplier->address ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm" >
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi Stock In ({{ $supplier->purchases->count() }})</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small" >
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>No. Pembelian</th>
                                <th>Tanggal</th>
                                <th>Staf Penginput</th>
                                <th>Item Produk</th>
                                <th class="text-end">Total Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->purchases as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="text-info font-monospace text-decoration-none">
                                        {{ $purchase->purchase_number }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $purchase->purchased_at ? $purchase->purchased_at->format('d/m/Y') : '-' }}</td>
                                <td>{{ $purchase->user->name ?? '-' }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($purchase->purchaseItems as $item)
                                        <li>{{ $item->product->name ?? '-' }} <span class="badge bg-secondary font-monospace">+{{ $item->quantity }}</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-end fw-semibold font-monospace">{{ $purchase->formatted_total }}</td>
                                <td>
                                    @if($purchase->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">Belum ada transaksi stock in dari pemasok ini.</td>
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
