@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
        <p class="text-muted mb-0">Welcome back, <span class="text-white fw-semibold">{{ auth()->user()->name }}</span>! Here's your store overview.</p>
    </div>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d M Y') }}</span>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(233,69,96,0.15);color:#e94560;">
                    <i class="bi bi-camera-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Products</div>
                    <div class="fs-4 fw-bold text-white">{{ number_format($totalProducts ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(13,110,253,0.15);color:#0d6efd;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Customers</div>
                    <div class="fs-4 fw-bold text-white">{{ number_format($totalCustomers ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(25,135,84,0.15);color:#198754;">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Today's Sales</div>
                    <div class="fs-4 fw-bold text-white">{{ $todaySales ?? 0 }}</div>
                    <div class="text-success small">Rp {{ number_format($todaySalesAmount ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(255,193,7,0.15);color:#ffc107;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Low Stock Products</div>
                    <div class="fs-4 fw-bold text-white">{{ $lowStockProducts->count() ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-4">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(111,66,193,0.15);color:#6f42c1;">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <div class="text-muted small">Categories</div>
                    <div class="fs-5 fw-bold text-white">{{ number_format($totalCategories ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(13,202,240,0.15);color:#0dcaf0;">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <div class="text-muted small">Suppliers</div>
                    <div class="fs-5 fw-bold text-white">{{ number_format($totalSuppliers ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="cs-card stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="background:rgba(253,126,20,0.15);color:#fd7e14;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="text-muted small">Monthly Sales</div>
                    <div class="fs-5 fw-bold text-white">Rp {{ number_format($monthlySales ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Sales --}}
    <div class="col-lg-6">
        <div class="cs-card card">
            <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
                <span><i class="bi bi-cart-check me-2 text-success"></i>Recent Sales</span>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-accent">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table cs-table mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales ?? [] as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none" style="color:#e94560;">
                                        {{ $sale->sale_number }}
                                    </a>
                                </td>
                                <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                                <td class="fw-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td class="text-muted small">{{ $sale->sold_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No recent sales</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Purchases --}}
    <div class="col-lg-6">
        <div class="cs-card card">
            <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
                <span><i class="bi bi-box-arrow-in-down me-2 text-info"></i>Recent Purchases</span>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-accent">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table cs-table mb-0">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPurchases ?? [] as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="text-decoration-none" style="color:#e94560;">
                                        {{ $purchase->purchase_number }}
                                    </a>
                                </td>
                                <td>{{ $purchase->supplier->name ?? '-' }}</td>
                                <td class="fw-semibold">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                <td class="text-muted small">{{ $purchase->purchased_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No recent purchases</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Alerts --}}
    <div class="col-12">
        <div class="cs-card card">
            <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
                <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Low Stock Alerts</span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-accent">View Products</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table cs-table mb-0">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts ?? [] as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                </td>
                                <td>{{ $product->minimum_stock }}</td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Low Stock</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-check-circle text-success me-2"></i>All products are well stocked!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
