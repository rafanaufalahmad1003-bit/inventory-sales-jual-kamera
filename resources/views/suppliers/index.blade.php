@extends('layouts.app')

@section('title', 'Data Pemasok (Supplier)')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-truck me-2 text-danger"></i>Data Pemasok (Supplier)</h3>
        <p class="text-muted small mb-0">Daftar distributor resmi dan pemasok kamera</p>
    </div>
    @if(auth()->user()->isOwner())
    <a href="{{ route('suppliers.create') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pemasok
    </a>
    @else
    <div class="badge bg-secondary px-3 py-2">
        <i class="bi bi-shield-lock me-1"></i> Mode {{ ucfirst(auth()->user()->role) }}: Hanya Owner yang dapat menambah/mengedit pemasok
    </div>
    @endif
</div>

@if(!auth()->user()->isOwner())
<div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: rgba(13, 202, 240, 0.15); color: #0dcaf0;">
    <i class="bi bi-info-circle-fill me-1"></i> <strong>Informasi Otorisasi:</strong> Sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>, Anda dapat melihat informasi pemasok untuk keperluan <strong>Stock In (Pembelian Stok)</strong>. Pengelolaan master data supplier dikelola oleh Owner.
</div>
@endif

<div class="card border-0 shadow-sm" >
    <div class="card-body p-3">
        <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-secondary-subtle-subtle" placeholder="Cari nama pemasok, kontak, telepon..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Reset</a>
            </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Pemasok</th>
                        <th>Contact Person</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $supplier)
                    <tr >
                        <td class="text-muted">{{ $suppliers->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $supplier->name }}</div>
                            <div class="text-muted small">{{ Str::limit($supplier->address, 35) ?? '-' }}</div>
                        </td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>
                            <a href="tel:{{ $supplier->phone }}" class="text-info text-decoration-none font-monospace small">
                                <i class="bi bi-telephone me-1"></i>{{ $supplier->phone }}
                            </a>
                        </td>
                        <td>
                            @if($supplier->email)
                            <a href="mailto:{{ $supplier->email }}" class="text-muted text-decoration-none small">
                                {{ $supplier->email }}
                            </a>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $supplier->purchases_count }} pembelian</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat Riwayat">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isOwner())
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Pemasok">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mengarsipkan pemasok ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada data pemasok kamera.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
