@extends('layouts.app')

@section('title', 'Kategori Kamera')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-white mb-0"><i class="bi bi-tags me-2 text-danger"></i>Kategori Kamera</h3>
        <p class="text-muted small mb-0">Kelola kategori produk kamera dan aksesoris</p>
    </div>
    @if(auth()->user()->isOwner())
    <a href="{{ route('categories.create') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('categories.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary text-light" placeholder="Cari nama atau deskripsi kategori..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Reset</a>
            </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
                <thead class="text-muted small text-uppercase" style="border-bottom: 2px solid var(--cs-border);">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        @if(auth()->user()->isOwner())
                        <th class="text-end" style="width: 150px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr style="border-bottom: 1px solid var(--cs-border);">
                        <td class="text-muted">{{ $categories->firstItem() + $index }}</td>
                        <td class="fw-semibold text-white">
                            <i class="bi bi-folder2-open text-warning me-2"></i>{{ $category->name }}
                        </td>
                        <td class="text-muted small">{{ $category->description ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary px-2 py-1">{{ $category->products_count }} item</span>
                        </td>
                        @if(auth()->user()->isOwner())
                        <td class="text-end">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isOwner() ? 5 : 4 }}" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada kategori kamera ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
