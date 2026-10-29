@extends('layouts.app')

@section('title', 'Data Pelanggan Kamera')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-white mb-0"><i class="bi bi-people me-2 text-danger"></i>Data Pelanggan (Customer)</h3>
        <p class="text-muted small mb-0">Daftar pelanggan studio foto, rental, fotografer & pembeli kamera</p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-person-plus me-1"></i> Tambah Pelanggan
    </a>
</div>

<div class="card border-0 shadow-sm" >
    <div class="card-body p-3">
        <form method="GET" action="{{ route('customers.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary-subtle-subtle text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-secondary-subtle-subtle" placeholder="Cari nama pelanggan, nomor HP, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Reset</a>
            </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" >
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Pelanggan</th>
                        <th>Telepon / WhatsApp</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Total Belanja</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                    <tr >
                        <td class="text-muted">{{ $customers->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold text-white">
                                <i class="bi bi-person-circle text-info me-1"></i>{{ $customer->name }}
                            </div>
                        </td>
                        <td>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" target="_blank" class="text-success text-decoration-none font-monospace small">
                                <i class="bi bi-whatsapp me-1"></i>{{ $customer->phone }}
                            </a>
                        </td>
                        <td>
                            @if($customer->email)
                            <span class="text-light small">{{ $customer->email }}</span>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ Str::limit($customer->address, 35) ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $customer->sales_count }} transaksi</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat Riwayat Belanja">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Data">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-person-x fs-2 d-block mb-2"></i>
                            Belum ada data pelanggan kamera.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
