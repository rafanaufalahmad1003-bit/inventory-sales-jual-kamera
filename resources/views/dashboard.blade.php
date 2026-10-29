@extends('layouts.app')
@section('title', 'Dashboard & Laporan Terpadu')

@section('content')
<!-- Header Title & Role Badge -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-grid-1x2-fill text-primary"></i> Dashboard & Laporan Terpadu
        </h3>
        <p class="text-muted small mb-0">
            Selamat datang, <strong class="text-dark">{{ auth()->user()->name }}</strong>. 
            Anda masuk sebagai 
            @if(auth()->user()->isOwner())
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-star-fill me-1"></i>OWNER (Akses Penuh)</span>
            @elseif(auth()->user()->isAdmin())
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-shield-fill me-1"></i>ADMIN (Operasional)</span>
            @else
                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"><i class="bi bi-person-fill me-1"></i>STAFF (Operasional)</span>
            @endif
        </p>
    </div>
    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
        <span class="badge bg-light text-dark border px-3 py-2 small shadow-sm">
            <i class="bi bi-calendar3 me-1 text-primary"></i> {{ now()->translatedFormat('l, d F Y') }}
        </span>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. STATS METRICS CARDS -->
<!-- ========================================== -->
<div class="row g-3 mb-4">
    <!-- Total Revenue / Penjualan -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold" style="font-size:0.75rem;">Total Pendapatan (Sales)</span>
                        <h4 class="fw-bold text-success font-monospace mb-1 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        <div class="text-muted small">
                            Hari ini: <strong class="text-dark">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</strong> ({{ $todaySalesCount }} trx)
                        </div>
                    </div>
                    <div class="bg-success-subtle text-success p-2 rounded-3">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Expense / Pembelian Stok -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-primary">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold" style="font-size:0.75rem;">Total Pengeluaran (Stock In)</span>
                        <h4 class="fw-bold text-primary font-monospace mb-1 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                        <div class="text-muted small">
                            Hari ini: <strong class="text-dark">Rp {{ number_format($todayExpense, 0, ',', '.') }}</strong> ({{ $todayPurchasesCount }} trx)
                        </div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-2 rounded-3">
                        <i class="bi bi-box-arrow-in-down fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gross Profit / Laba Bersih Transaksi -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-4 {{ $totalProfit >= 0 ? 'border-info' : 'border-danger' }}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold" style="font-size:0.75rem;">Estimasi Laba Bersih</span>
                        <h4 class="fw-bold {{ $totalProfit >= 0 ? 'text-info' : 'text-danger' }} font-monospace mb-1 mt-1">
                            Rp {{ number_format($totalProfit, 0, ',', '.') }}
                        </h4>
                        <div class="text-muted small">
                            Bulan ini: <strong class="{{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($monthlyProfit, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <div class="bg-info-subtle text-info p-2 rounded-3">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Asset Value -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-4 border-warning">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold" style="font-size:0.75rem;">Nilai Aset Stok Fisik</span>
                        <h4 class="fw-bold text-dark font-monospace mb-1 mt-1">Rp {{ number_format($inventoryAssetValue, 0, ',', '.') }}</h4>
                        <div class="text-muted small">
                            Potensi omset: <strong class="text-success">Rp {{ number_format($inventoryPotentialValue, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-2 rounded-3">
                        <i class="bi bi-camera-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. QUICK ACTION & MASTER SUMMARY BAR -->
<!-- ========================================== -->
<div class="card border-0 shadow-sm mb-4 bg-light">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-3 text-secondary small">
                    <div><i class="bi bi-camera me-1 text-primary"></i>Produk: <strong class="text-dark">{{ $totalProducts }}</strong> item</div>
                    <div><i class="bi bi-tags me-1 text-warning"></i>Kategori: <strong class="text-dark">{{ $totalCategories }}</strong></div>
                    <div><i class="bi bi-truck me-1 text-info"></i>Pemasok: <strong class="text-dark">{{ $totalSuppliers }}</strong></div>
                    <div><i class="bi bi-people me-1 text-success"></i>Pelanggan: <strong class="text-dark">{{ $totalCustomers }}</strong></div>
                    @if($lowStockProducts->count() > 0)
                    <div class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Stok Menipis: {{ $lowStockProducts->count() }} item</div>
                    @endif
                </div>
            </div>
            <div class="col-md-5 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="bi bi-box-arrow-in-down me-1"></i> + Stock In Baru
                </a>
                <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm shadow-sm">
                    <i class="bi bi-cart-plus me-1"></i> + Penjualan Baru
                </a>
                @if(auth()->user()->isOwner())
                <a href="{{ route('products.create') }}" class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> + Kamera (Owner)
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. UNIFIED TABS: SEMUA HALAMAN JADI SATU -->
<!-- ========================================== -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white p-0 border-bottom">
        <ul class="nav nav-tabs card-header-tabs m-0 px-3 border-0" id="unifiedDashboardTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active py-3 px-3 text-dark fw-semibold" id="financial-tab" data-bs-toggle="tab" data-bs-target="#tab-financial" type="button" role="tab">
                    <i class="bi bi-currency-dollar text-success me-1"></i> Laporan Keuangan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link py-3 px-3 text-dark fw-semibold" id="sales-tab" data-bs-toggle="tab" data-bs-target="#tab-sales" type="button" role="tab">
                    <i class="bi bi-cart-check text-primary me-1"></i> Transaksi Penjualan ({{ $recentSales->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link py-3 px-3 text-dark fw-semibold" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#tab-purchases" type="button" role="tab">
                    <i class="bi bi-box-arrow-in-down text-info me-1"></i> Pembelian / Stock In ({{ $recentPurchases->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link py-3 px-3 text-dark fw-semibold" id="stock-tab" data-bs-toggle="tab" data-bs-target="#tab-stock" type="button" role="tab">
                    <i class="bi bi-exclamation-triangle text-danger me-1"></i> Peringatan Stok Menipis 
                    @if($lowStockProducts->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $lowStockProducts->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link py-3 px-3 text-dark fw-semibold" id="products-tab" data-bs-toggle="tab" data-bs-target="#tab-products" type="button" role="tab">
                    <i class="bi bi-camera text-secondary me-1"></i> Ringkasan Master Produk
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="unifiedDashboardTabsContent">

            <!-- TAB 1: LAPORAN KEUANGAN (FINANCIAL REPORT) -->
            <div class="tab-pane fade show active" id="tab-financial" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>Laporan Keuangan & Arus Kas</h5>
                        <p class="text-muted small mb-0">Laporan pemasukan, pengeluaran stok, dan margin keuntungan untuk Owner, Admin, dan Staff</p>
                    </div>
                    
                    <!-- Date Filter Form -->
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2 align-items-center mt-2 mt-md-0">
                        <span class="text-muted small fw-semibold">Periode:</span>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                        <span class="text-muted small">s/d</span>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i> Tampilkan</button>
                        @if(request('start_date') || request('end_date'))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </form>
                </div>

                <!-- Financial Highlight Box -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Pemasukan Periode Ini</span>
                            <h4 class="fw-bold text-success font-monospace mb-0">Rp {{ number_format($periodRevenue, 0, ',', '.') }}</h4>
                            <span class="small text-muted">{{ $periodSales->count() }} transaksi penjualan</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Pengeluaran Stok Periode Ini</span>
                            <h4 class="fw-bold text-danger font-monospace mb-0">Rp {{ number_format($periodExpense, 0, ',', '.') }}</h4>
                            <span class="small text-muted">{{ $periodPurchases->count() }} transaksi pengadaan stok</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Laba / Arus Kas Bersih</span>
                            <h4 class="fw-bold font-monospace mb-0 {{ $periodProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($periodProfit, 0, ',', '.') }}
                            </h4>
                            <span class="small text-muted">{{ $periodProfit >= 0 ? 'Surplus Keuangan Positif' : 'Defisit (Pengadaan Stok Lebih Besar)' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Breakdown Tables -->
                <div class="row g-4">
                    <!-- Revenue Breakdown -->
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3">
                            <h6 class="fw-bold text-dark mb-3 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-arrow-down-left-circle-fill text-success me-1"></i> Rincian Pemasukan (Penjualan)</span>
                                <span class="badge bg-success-subtle text-success">{{ $periodSales->count() }} Data</span>
                            </h6>
                            <div class="table-responsive" style="max-height: 320px;">
                                <table class="table table-sm table-hover align-middle mb-0 small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Faktur</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th class="text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($periodSales as $ps)
                                        <tr>
                                            <td>
                                                <a href="{{ route('sales.show', $ps) }}" class="fw-semibold text-primary font-monospace text-decoration-none">
                                                    {{ $ps->sale_number }}
                                                </a>
                                            </td>
                                            <td class="text-muted">{{ $ps->sold_at ? $ps->sold_at->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $ps->customer->name ?? 'Walk-in' }}</td>
                                            <td class="text-end fw-bold font-monospace text-success">
                                                Rp {{ number_format($ps->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada pemasukan pada periode ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Breakdown -->
                    <div class="col-lg-6">
                        <div class="border rounded-3 p-3">
                            <h6 class="fw-bold text-dark mb-3 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-arrow-up-right-circle-fill text-danger me-1"></i> Rincian Pengeluaran (Stock In)</span>
                                <span class="badge bg-danger-subtle text-danger">{{ $periodPurchases->count() }} Data</span>
                            </h6>
                            <div class="table-responsive" style="max-height: 320px;">
                                <table class="table table-sm table-hover align-middle mb-0 small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. Pembelian</th>
                                            <th>Tanggal</th>
                                            <th>Pemasok</th>
                                            <th class="text-end">Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($periodPurchases as $pp)
                                        <tr>
                                            <td>
                                                <a href="{{ route('purchases.show', $pp) }}" class="fw-semibold text-primary font-monospace text-decoration-none">
                                                    {{ $pp->purchase_number }}
                                                </a>
                                            </td>
                                            <td class="text-muted">{{ $pp->purchased_at ? $pp->purchased_at->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $pp->supplier->name ?? '-' }}</td>
                                            <td class="text-end fw-bold font-monospace text-danger">
                                                Rp {{ number_format($pp->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada pengeluaran pada periode ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: TRANSAKSI PENJUALAN (SALES) -->
            <div class="tab-pane fade" id="tab-sales" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-cart-check me-2 text-primary"></i>Riwayat Transaksi Penjualan Terkini</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i> Input Penjualan</a>
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">Buka Halaman Penuh & Filter <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Kasir / Petugas</th>
                                <th>Item Kamera</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-primary font-monospace text-decoration-none">
                                        {{ $sale->sale_number }}
                                    </a>
                                </td>
                                <td class="text-muted small">{{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $sale->customer->name ?? 'Pelanggan Umum' }}</div>
                                    <span class="text-muted small">{{ $sale->customer->phone ?? '' }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $sale->user->name ?? '-' }}</span></td>
                                <td class="small">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($sale->saleItems->take(2) as $sItem)
                                        <li>{{ $sItem->product->name ?? '-' }} <span class="badge bg-light text-dark border">{{ $sItem->quantity }}x</span></li>
                                        @endforeach
                                        @if($sale->saleItems->count() > 2)
                                        <li class="text-muted small">+{{ $sale->saleItems->count() - 2 }} item lainnya</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-end fw-bold font-monospace text-success">{{ $sale->formatted_total }}</td>
                                <td class="text-center">
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Selesai</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Lihat Faktur">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi penjualan kamera.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: PEMBELIAN / STOCK IN -->
            <div class="tab-pane fade" id="tab-purchases" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-box-arrow-in-down me-2 text-info"></i>Riwayat Stock In (Pembelian Stok)</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Input Stock In</a>
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm">Buka Halaman Penuh & Filter <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pembelian</th>
                                <th>Tanggal</th>
                                <th>Pemasok (Distributor)</th>
                                <th>Petugas Input</th>
                                <th>Item Kamera</th>
                                <th class="text-end">Total Biaya</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPurchases as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="fw-semibold text-primary font-monospace text-decoration-none">
                                        {{ $purchase->purchase_number }}
                                    </a>
                                </td>
                                <td class="text-muted small">{{ $purchase->purchased_at ? $purchase->purchased_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $purchase->supplier->name ?? '-' }}</div>
                                    <span class="text-muted small">{{ $purchase->supplier->phone ?? '' }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $purchase->user->name ?? '-' }}</span></td>
                                <td class="small">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($purchase->purchaseItems->take(2) as $pItem)
                                        <li>{{ $pItem->product->name ?? '-' }} <span class="badge bg-primary-subtle text-primary border border-primary-subtle">+{{ $pItem->quantity }}</span></li>
                                        @endforeach
                                        @if($purchase->purchaseItems->count() > 2)
                                        <li class="text-muted small">+{{ $purchase->purchaseItems->count() - 2 }} item lainnya</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-end fw-bold font-monospace text-danger">{{ $purchase->formatted_total }}</td>
                                <td class="text-center">
                                    @if($purchase->status === 'completed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Selesai (Stok Masuk)</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Batal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-primary py-0 px-2">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi pembelian stok kamera.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: PERINGATAN STOK MENIPIS (LOW STOCK ALERTS) -->
            <div class="tab-pane fade" id="tab-stock" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Daftar Produk Kamera Stok Kritis</h5>
                        <p class="text-muted small mb-0">Kamera dengan jumlah stok di bawah atau sama dengan batas minimum stok</p>
                    </div>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i> Buat Pesanan Pasokan (Stock In)</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode SKU</th>
                                <th>Nama Kamera</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Batas Minimum</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $prod)
                            <tr>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ $prod->sku }}</span></td>
                                <td class="fw-semibold text-dark">{{ $prod->name }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ $prod->category->name ?? '-' }}</span></td>
                                <td class="text-center">
                                    @if($prod->stock <= 0)
                                        <span class="badge bg-danger px-2 py-1">Habis (0)</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1">{{ $prod->stock }} unit</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted font-monospace">{{ $prod->minimum_stock }} unit</td>
                                <td class="text-end font-monospace">{{ $prod->formatted_purchase_price }}</td>
                                <td class="text-center">
                                    @if($prod->stock <= 0)
                                        <span class="badge bg-danger text-white">Wajib Restock</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Stok Kritis</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-plus me-1"></i>Stock In
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                                    Semua stok kamera dalam kondisi aman dan mencukupi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: RINGKASAN MASTER PRODUK -->
            <div class="tab-pane fade" id="tab-products" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-camera me-2 text-secondary"></i>Katalog Produk Kamera Terdaftar</h5>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->isOwner())
                        <a href="{{ route('products.create') }}" class="btn btn-danger btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah Produk (Owner)</a>
                        @endif
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua ({{ $totalProducts }}) <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center">Stok</th>
                                <th class="text-end">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productsList as $p)
                            <tr>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ $p->sku }}</span></td>
                                <td class="fw-semibold text-dark">{{ $p->name }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">{{ $p->category->name ?? '-' }}</span></td>
                                <td class="text-end text-muted font-monospace">{{ $p->formatted_purchase_price }}</td>
                                <td class="text-end fw-semibold text-success font-monospace">{{ $p->formatted_selling_price }}</td>
                                <td class="text-center">
                                    @if($p->stock <= 0)
                                        <span class="badge bg-danger">0</span>
                                    @elseif($p->isLowStock())
                                        <span class="badge bg-warning text-dark">{{ $p->stock }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $p->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('products.show', $p) }}" class="btn btn-sm btn-outline-primary py-0 px-2">Lihat</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
