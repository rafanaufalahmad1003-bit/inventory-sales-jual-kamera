@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan Kamera')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-cart-plus text-success me-2"></i>Transaksi Penjualan Kamera (Stock Out)</h4>
                <p class="text-muted small mb-0">Stok produk kamera akan berkurang secara otomatis jika stok tersedia mencukupi</p>
            </div>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
            @csrf

            <!-- Header Card -->
            <div class="card border-0 shadow-sm mb-3" >
                <div class="card-header bg-transparent border-0 pt-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-person-circle text-success me-1"></i> Informasi Pembeli & Tanggal Penjualan</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Pilih Pelanggan (Customer)</label>
                            <select name="customer_id" class="form-select border-secondary-subtle-subtle @error('customer_id') is-invalid @enderror">
                                <option value="">-- Pelanggan Umum (Walk-in Customer) --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('customer_id', request('customer_id')) == $customer->id) ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small">Pelanggan baru? <a href="{{ route('customers.create') }}" class="text-info text-decoration-none" target="_blank">+ Tambah Pelanggan</a></div>
                            @error('customer_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Tanggal & Waktu Penjualan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sold_at" class="form-control border-secondary-subtle-subtle @error('sold_at') is-invalid @enderror" value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('sold_at') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-light fw-semibold">Catatan Transaksi</label>
                        <input type="text" name="notes" class="form-control border-secondary-subtle-subtle @error('notes') is-invalid @enderror" value="{{ old('notes') }}" placeholder="Contoh: Pembayaran tunai/transfer, garansi toko 1 tahun...">
                        @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="card border-0 shadow-sm mb-3" >
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-camera text-success me-1"></i> Produk Kamera Yang Dibeli</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addRowBtn">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Item Kamera
                    </button>
                </div>
                <div class="card-body p-3">
                    @error('items')
                        <div class="alert alert-danger py-2 small mb-3">
                            <i class="bi bi-exclamation-octagon-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="itemsTable" >
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th style="min-width: 280px;">Produk Kamera & Stok Tersedia <span class="text-danger">*</span></th>
                                    <th style="width: 140px;">Kuantitas (Qty) <span class="text-danger">*</span></th>
                                    <th style="width: 200px;">Harga Satuan (Rp)</th>
                                    <th style="width: 180px;" class="text-end">Subtotal (Rp)</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row" data-index="0">
                                    <td>
                                        <select name="items[0][product_id]" class="form-select border-secondary-subtle-subtle product-select" required>
                                            <option value="">-- Pilih Kamera / Aksesoris --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-selling-price="{{ (int)$p->selling_price }}" data-stock="{{ $p->stock }}">
                                                    [{{ $p->sku }}] {{ $p->name }} — Tersedia: {{ $p->stock }} unit
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="stock-warning text-danger small mt-1 d-none"><i class="bi bi-exclamation-triangle"></i> Kuantitas melebihi stok yang tersedia!</div>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control border-secondary-subtle-subtle text-center qty-input" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <span class="form-control border-secondary-subtle-subtle font-monospace price-display">Rp 0</span>
                                    </td>
                                    <td class="text-end fw-semibold font-monospace text-success subtotal-display">
                                        Rp 0
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-dark fs-6">TOTAL PENJUALAN:</td>
                                    <td class="text-end fw-bold text-success font-monospace fs-5" id="grandTotalDisplay">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold" id="submitBtn">
                    <i class="bi bi-check2-circle me-1"></i> Proses Penjualan & Kurangi Stok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;
    const itemsBody = document.getElementById('itemsBody');
    const addRowBtn = document.getElementById('addRowBtn');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculateRow(row) {
        const select = row.querySelector('.product-select');
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption?.getAttribute('data-selling-price')) || 0;
        const maxStock = parseInt(selectedOption?.getAttribute('data-stock')) || 0;
        const qtyInput = row.querySelector('.qty-input');
        const qty = parseFloat(qtyInput.value) || 0;
        const warning = row.querySelector('.stock-warning');

        row.querySelector('.price-display').textContent = formatRupiah(price);
        const subtotal = qty * price;
        row.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);

        // Check stock warning
        if (selectedOption && selectedOption.value) {
            if (qty > maxStock) {
                warning.classList.remove('d-none');
                warning.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Melebihi stok! Stok tersedia hanya ${maxStock} unit.`;
                qtyInput.classList.add('is-invalid');
            } else {
                warning.classList.add('d-none');
                qtyInput.classList.remove('is-invalid');
            }
        } else {
            warning.classList.add('d-none');
            qtyInput.classList.remove('is-invalid');
        }

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.getAttribute('data-selling-price')) || 0;
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            grandTotal += (qty * price);
        });
        grandTotalDisplay.textContent = formatRupiah(grandTotal);
    }

    function attachRowEvents(row) {
        const productSelect = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const removeBtn = row.querySelector('.remove-row-btn');

        productSelect.addEventListener('change', () => calculateRow(row));
        qtyInput.addEventListener('input', () => calculateRow(row));

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calculateGrandTotal();
                    updateRemoveButtons();
                }
            });
        }
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(r => {
            const btn = r.querySelector('.remove-row-btn');
            if (btn) btn.disabled = (rows.length <= 1);
        });
    }

    addRowBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.setAttribute('data-index', rowIndex);

        const select = newRow.querySelector('.product-select');
        select.name = `items[${rowIndex}][product_id]`;
        select.value = '';

        const qty = newRow.querySelector('.qty-input');
        qty.name = `items[${rowIndex}][quantity]`;
        qty.value = 1;
        qty.classList.remove('is-invalid');

        newRow.querySelector('.price-display').textContent = 'Rp 0';
        newRow.querySelector('.subtotal-display').textContent = 'Rp 0';
        newRow.querySelector('.stock-warning').classList.add('d-none');

        const removeBtn = newRow.querySelector('.remove-row-btn');
        removeBtn.disabled = false;

        itemsBody.appendChild(newRow);
        attachRowEvents(newRow);
        updateRemoveButtons();
        rowIndex++;
    });

    document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
    updateRemoveButtons();
});
</script>
@endsection
