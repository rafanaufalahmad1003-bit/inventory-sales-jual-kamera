@extends('layouts.app')

@section('title', 'Tambah Kategori Kamera')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-folder-plus text-danger me-2"></i>Tambah Kategori Kamera</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <div class="card border-0 shadow-sm" >
            <div class="card-body p-4">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-secondary-subtle-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Mirrorless Cameras" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control border-secondary-subtle-subtle @error('description') is-invalid @enderror" placeholder="Keterangan singkat tentang kategori kamera ini...">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger" style="background-color: var(--cs-accent); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
