@extends('layouts.app')

@section('title', 'Input Transaksi Pembelian (Stock In)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-box-arrow-in-down text-primary me-2"></i>Transaksi Pembelian Kamera (Stock In)</h4>
                <p class="text-muted small mb-0">Menambah stok fisik kamera secara otomatis setelah transaksi berhasil disimpan</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
            @csrf

            <!-- Header Card -->
            <div class="card border-0 shadow-sm mb-3" >
                <div class="card-header bg-transparent border-0 pt-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle text-primary me-1"></i> Data Pemasok & Waktu Pembelian</h6>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Pilih Pemasok (Supplier) <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select border-secondary-subtle-subtle @error('supplier_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Distributor / Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} ({{ $supplier->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light fw-semibold">Tanggal & Waktu Transaksi <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="purchased_at" class="form-control border-secondary-subtle-subtle @error('purchased_at') is-invalid @enderror" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('purchased_at') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-light fw-semibold">Catatan / Keterangan Pembelian</label>
                        <input type="text" name="notes" class="form-control border-secondary-subtle-subtle @error('notes') is-invalid @enderror" value="{{ old('notes') }}" placeholder="Contoh: Pengiriman batch 1 invoice #CANON-9902...">
                        @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="card border-0 shadow-sm mb-3" >
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-cart-plus text-primary me-1"></i> Rincian Produk Kamera (Stock In)</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris Produk
                    </button>
                </div>
                <div class="card-body p-3">
                    @error('items') <div class="alert alert-danger py-2 small mb-3">{{ $message }}</div> @enderror

                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="itemsTable" >
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th style="min-width: 280px;">Produk Kamera <span class="text-danger">*</span></th>
                                    <th style="width: 140px;">Kuantitas (Qty) <span class="text-danger">*</span></th>
                                    <th style="width: 200px;">Harga Beli Satuan (Rp) <span class="text-danger">*</span></th>
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
                                                <option value="{{ $p->id }}" data-purchase-price="{{ (int)$p->purchase_price }}">
                                                    [{{ $p->sku }}] {{ $p->name }} (Stok saat ini: {{ $p->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control border-secondary-subtle-subtle text-center qty-input" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" step="1000" name="items[0][unit_cost]" class="form-control border-secondary-subtle-subtle font-monospace price-input" min="0" placeholder="0" required>
                                    </td>
                                    <td class="text-end fw-semibold font-monospace text-light subtotal-display">
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
                                    <td colspan="3" class="text-end fw-bold text-dark fs-6">TOTAL KESELURUHAN:</td>
                                    <td class="text-end fw-bold text-success font-monospace fs-5" id="grandTotalDisplay">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi Pembelian & Tambah Stok
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
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        row.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            grandTotal += (qty * price);
        });
        grandTotalDisplay.textContent = formatRupiah(grandTotal);
    }

    function attachRowEvents(row) {
        const productSelect = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const removeBtn = row.querySelector('.remove-row-btn');

        productSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const defaultPrice = selectedOption.getAttribute('data-purchase-price');
            if (defaultPrice && (!priceInput.value || priceInput.value == 0)) {
                priceInput.value = defaultPrice;
            }
            calculateRow(row);
        });

        qtyInput.addEventListener('input', () => calculateRow(row));
        priceInput.addEventListener('input', () => calculateRow(row));

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

        // Update name attributes with new index
        const select = newRow.querySelector('.product-select');
        select.name = `items[${rowIndex}][product_id]`;
        select.value = '';

        const qty = newRow.querySelector('.qty-input');
        qty.name = `items[${rowIndex}][quantity]`;
        qty.value = 1;

        const price = newRow.querySelector('.price-input');
        price.name = `items[${rowIndex}][unit_cost]`;
        price.value = '';

        newRow.querySelector('.subtotal-display').textContent = 'Rp 0';

        const removeBtn = newRow.querySelector('.remove-row-btn');
        removeBtn.disabled = false;

        itemsBody.appendChild(newRow);
        attachRowEvents(newRow);
        updateRemoveButtons();
        rowIndex++;
    });

    // Attach initial row
    document.querySelectorAll('.item-row').forEach(row => attachRowEvents(row));
    updateRemoveButtons();
});
</script>
@endsection
