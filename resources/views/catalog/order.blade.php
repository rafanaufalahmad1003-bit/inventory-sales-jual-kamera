@extends('layouts.app')

@section('title', 'Form Pemesanan Kamera - ' . $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-white mb-0"><i class="bi bi-cart-check-fill text-danger me-2"></i>Form Pemesanan Kamera</h4>
                <p class="text-muted small mb-0">Isi data pengiriman Anda untuk menyelesaikan pemesanan kamera</p>
            </div>
            <a href="{{ route('catalog.show', $product) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row g-3">
            <!-- Product Summary Card -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h6 class="fw-bold text-white mb-0"><i class="bi bi-camera me-1 text-danger"></i> Ringkasan Produk</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="text-center bg-dark p-2 rounded mb-3">
                            @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                            @else
                            <div class="py-4 text-muted"><i class="bi bi-camera fs-1"></i></div>
                            @endif
                        </div>

                        <h5 class="fw-bold text-white mb-1">{{ $product->name }}</h5>
                        <span class="badge bg-secondary mb-2">{{ $product->category->name ?? 'Kamera' }}</span>

                        <div class="p-3 bg-dark rounded border border-secondary mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Harga Satuan:</span>
                                <span class="fw-bold text-light font-monospace">{{ $product->formatted_selling_price }}</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Stok Tersedia:</span>
                                <span class="badge bg-success font-monospace">{{ $product->stock }} unit</span>
                            </div>
                            <hr class="my-2 border-secondary">
                            <div class="d-flex justify-content-between text-white fw-bold">
                                <span>Estimasi Total:</span>
                                <span class="text-success font-monospace fs-5" id="orderTotalDisplay">{{ $product->formatted_selling_price }}</span>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 small mb-0 border-0" style="background: rgba(13, 202, 240, 0.15); color: #0dcaf0;">
                            <i class="bi bi-shield-check me-1"></i> Pembayaran dapat dilakukan via transfer bank atau saat penjemputan barang di toko (COD).
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Checkout Form -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h6 class="fw-bold text-white mb-0"><i class="bi bi-person-lines-fill me-1 text-danger"></i> Data Pembeli & Tujuan Pengiriman</h6>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <form action="{{ route('catalog.processOrder', $product) }}" method="POST" id="orderForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label text-light fw-semibold">Nama Lengkap Pembeli <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control bg-dark border-secondary text-light @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', auth()->user()->name ?? ($customer->name ?? '')) }}" placeholder="Contoh: Reza Rahardian" required>
                                @error('customer_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-light fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_phone" class="form-control bg-dark border-secondary text-light @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', auth()->user()->phone ?? ($customer->phone ?? '')) }}" placeholder="081234567890" required>
                                    @error('customer_phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-light fw-semibold">Email</label>
                                    <input type="email" name="customer_email" class="form-control bg-dark border-secondary text-light @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', auth()->user()->email ?? ($customer->email ?? '')) }}" placeholder="nama@email.com">
                                    @error('customer_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-light fw-semibold">Jumlah Unit Yang Dipesan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-box"></i></span>
                                    <input type="number" name="quantity" id="quantityInput" class="form-control bg-dark border-secondary text-light @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" max="{{ $product->stock }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-muted">unit (Maks. {{ $product->stock }})</span>
                                </div>
                                <div id="stockAlert" class="text-danger small mt-1 d-none"><i class="bi bi-exclamation-triangle"></i> Jumlah melebihi stok yang tersedia!</div>
                                @error('quantity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-light fw-semibold">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="customer_address" rows="2" class="form-control bg-dark border-secondary text-light @error('customer_address') is-invalid @enderror" placeholder="Alamat jalan, nomor rumah/studio, kota/kabupaten..." required>{{ old('customer_address', auth()->user()->address ?? ($customer->address ?? '')) }}</textarea>
                                @error('customer_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-light fw-semibold">Catatan Khusus (Opsional)</label>
                                <input type="text" name="notes" class="form-control bg-dark border-secondary text-light @error('notes') is-invalid @enderror" value="{{ old('notes') }}" placeholder="Contoh: Packing kayu tebal, kirim hari Sabtu...">
                                @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-6" id="confirmOrderBtn" style="background-color: var(--cs-accent); border:none;">
                                <i class="bi bi-check2-circle me-1"></i> Konfirmasi & Pesan Kamera Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('quantityInput');
    const totalDisplay = document.getElementById('orderTotalDisplay');
    const alertBox = document.getElementById('stockAlert');
    const confirmBtn = document.getElementById('confirmOrderBtn');

    const unitPrice = {{ (int) $product->selling_price }};
    const maxStock = {{ (int) $product->stock }};

    function updateTotal() {
        const qty = parseInt(qtyInput.value) || 0;
        if (qty > maxStock) {
            alertBox.classList.remove('d-none');
            qtyInput.classList.add('is-invalid');
            confirmBtn.disabled = true;
        } else if (qty < 1) {
            alertBox.classList.remove('d-none');
            alertBox.textContent = 'Minimal pemesanan adalah 1 unit.';
            qtyInput.classList.add('is-invalid');
            confirmBtn.disabled = true;
        } else {
            alertBox.classList.add('d-none');
            qtyInput.classList.remove('is-invalid');
            confirmBtn.disabled = false;
        }

        const total = qty * unitPrice;
        totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    qtyInput.addEventListener('input', updateTotal);
});
</script>
@endsection
