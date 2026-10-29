@extends('layouts.app')

@section('title', 'Bukti Pesanan - ' . $sale->sale_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <div class="display-5 text-success mb-2"><i class="bi bi-check-circle-fill"></i></div>
            <h3 class="fw-bold text-dark mb-1">Terima Kasih Atas Pesanan Anda!</h3>
            <p class="text-muted">Pesanan kamera Anda telah berhasil diproses oleh sistem CameraStore.</p>
        </div>

        <div class="card border-0 shadow-lg" >
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4 pb-0">
                <div>
                    <h5 class="fw-bold text-dark mb-0">📷 CameraStore Indonesia</h5>
                    <span class="text-muted small">Faktur Pemesanan Resmi</span>
                </div>
                <div class="text-end">
                    <span class="badge bg-dark border border-secondary-subtle text-info font-monospace fs-6">{{ $sale->sale_number }}</span>
                    <div class="text-muted small mt-1">{{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : '-' }}</div>
                </div>
            </div>

            <div class="card-body p-4">
                <hr class="border-secondary-subtle my-3">

                <!-- Customer Info -->
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span class="text-muted small d-block">Tujuan Pengiriman / Pemesan:</span>
                        <div class="fw-bold text-dark">{{ $sale->customer->name ?? 'Pelanggan Umum' }}</div>
                        <div class="text-muted small">{{ $sale->customer->phone ?? '' }}</div>
                        <div class="text-light small mt-1">{{ $sale->customer->address ?? '' }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted small d-block">Status Pesanan:</span>
                        <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>Pesanan Dikonfirmasi</span>
                        <div class="text-muted small mt-2">Catatan: {{ $sale->notes ?? '-' }}</div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table align-middle mb-0" >
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th>Item Kamera</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleItems as $item)
                            <tr >
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->product->name ?? '-' }}</div>
                                    <span class="badge bg-dark border border-secondary-subtle text-muted small font-monospace">{{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center fw-bold text-light font-monospace">{{ $item->quantity }} unit</td>
                                <td class="text-end font-monospace">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold font-monospace text-success">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-dark fs-6">TOTAL PEMBAYARAN:</td>
                                <td class="text-end fw-bold text-success font-monospace fs-4">{{ $sale->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-dark border border-secondary-subtle small mb-0">
                    <h6 class="fw-bold text-light mb-1"><i class="bi bi-info-circle me-1 text-info"></i> Petunjuk Pembayaran & Pengambilan:</h6>
                    <p class="text-muted mb-0">Silakan simpan nomor faktur <strong>{{ $sale->sale_number }}</strong> ini sebagai bukti pemesanan yang sah. Tim Customer Care kami akan menghubungi WhatsApp Anda untuk konfirmasi nomor resi pengiriman atau penjadwalan pengambilan unit di toko.</p>
                </div>
            </div>

            <div class="card-footer bg-transparent border-0 d-flex justify-content-between p-4 pt-0">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
                </a>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-light">
                        <i class="bi bi-printer me-1"></i> Cetak Faktur
                    </button>
                    @auth
                    @if(auth()->user()->isPelanggan())
                    <a href="{{ route('catalog.myOrders') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
                        <i class="bi bi-bag-check me-1"></i> Lihat Pesanan Saya
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
