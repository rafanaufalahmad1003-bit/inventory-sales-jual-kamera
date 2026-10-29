@extends('layouts.app')

@section('title', 'Manajemen Pengguna (Users)')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold text-white mb-0"><i class="bi bi-people-fill me-2 text-danger"></i>Manajemen Pengguna & Hak Akses</h3>
        <p class="text-muted small mb-0">Hanya Owner yang berhak mengelola akun Owner, Admin, Staff, dan Pelanggan</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-danger" style="background-color: var(--cs-accent); border:none;">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
    </a>
</div>

<div class="card border-0 shadow-sm" style="background: var(--cs-card-bg); border: 1px solid var(--cs-border);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control bg-dark border-secondary text-light" placeholder="Cari nama, email pengguna..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select bg-dark border-secondary text-light">
                    <option value="">-- Semua Role --</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner (Pemilik)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan (Customer)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            @if(request('search') || request('role'))
            <div class="col-md-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Reset</a>
            </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
                <thead class="text-muted small text-uppercase" style="border-bottom: 2px solid var(--cs-border);">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Nama Pengguna</th>
                        <th>Email Login</th>
                        <th>Role Akses</th>
                        <th>Telepon</th>
                        <th>Izin Hak Akses</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr style="border-bottom: 1px solid var(--cs-border);">
                        <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-semibold text-white">
                                <i class="bi bi-person-fill text-muted me-1"></i>{{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge bg-secondary ms-1 small">Anda</span>
                                @endif
                            </div>
                        </td>
                        <td><span class="text-light small font-monospace">{{ $user->email }}</span></td>
                        <td>
                            <span class="{{ $user->getRoleBadgeClass() }} px-2 py-1">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $user->phone ?? '-' }}</td>
                        <td class="small">
                            @if($user->isOwner())
                                <span class="text-danger fw-semibold"><i class="bi bi-star-fill me-1"></i>Semua Akses (Owner)</span>
                            @elseif($user->isAdminOrStaff())
                                <span class="text-info"><i class="bi bi-check2 me-1"></i>Stock In, Sales, View</span>
                            @elseif($user->isPelanggan())
                                <span class="text-success"><i class="bi bi-bag me-1"></i>Pesan Kamera Online</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Akun">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
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
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
