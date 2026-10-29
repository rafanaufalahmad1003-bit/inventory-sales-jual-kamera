@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-white mb-0"><i class="bi bi-bag-check me-2 text-danger"></i>Riwayat Pesanan Saya</h3>
        <p class="text-muted small mb-0">Daftar pesanan kamera & aksesoris yang pernah Anda pesan</p>
    </div>
    <a href="{{ route('catalog.index') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-camera me-1"></i> Beli Kamera Lainnya
    </a>
</div>

<div class="card border-0 shadow-sm" >
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal Pesan</th>
                        <th>Kamera / Item Yang Dipesan</th>
                        <th class="text-end">Total Pembayaran</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr >
                        <td>
                            <span class="fw-semibold text-info font-monospace">{{ $sale->sale_number }}</span>
                        </td>
                        <td class="text-muted small">{{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($sale->saleItems as $item)
                                <li>
                                    <span class="text-light">{{ $item->product->name ?? 'Produk Kamera' }}</span>
                                    <span class="badge bg-secondary font-monospace ms-1">{{ $item->quantity }}x</span>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end fw-bold text-success font-monospace">{{ $sale->formatted_total }}</td>
                        <td class="text-center">
                            @if($sale->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('catalog.invoice', $sale) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-receipt me-1"></i> Lihat Faktur
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-bag-x display-4 d-block mb-3"></i>
                            <h5 class="text-white">Belum Ada Riwayat Pesanan</h5>
                            <p class="text-muted mb-3">Anda belum pernah memesan kamera di toko kami.</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
                                Jelajahi Katalog Kamera
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($sales, 'links'))
        <div class="mt-3">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
