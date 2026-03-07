{{-- resources/views/admin/mitra/create.blade.php --}}
@extends('layouts.app')
@section('title','Tambah Mitra')
@section('page-title','Tambah Mitra Baru')
@section('page-sub','Formulir tambah mitra magang')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<form action="{{ route('admin.mitra.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-building me-2 text-primary"></i>Data Perusahaan</span></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror" value="{{ old('nama_perusahaan') }}">
                @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Bidang Usaha <span class="text-danger">*</span></label>
                    <input type="text" name="bidang_usaha" class="form-control @error('bidang_usaha') is-invalid @enderror" value="{{ old('bidang_usaha') }}">
                    @error('bidang_usaha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Logo Perusahaan</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-user me-2 text-success"></i>Data Kontak</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Kontak <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kontak" class="form-control @error('nama_kontak') is-invalid @enderror" value="{{ old('nama_kontak') }}">
                    @error('nama_kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan Kontak</label>
                    <input type="text" name="jabatan_kontak" class="form-control" value="{{ old('jabatan_kontak') }}">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Email Perusahaan <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-cog me-2 text-warning"></i>Pengaturan</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kuota Magang</label>
                    <input type="number" name="kuota_magang" class="form-control" value="{{ old('kuota_magang', 5) }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : 'selected' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan</button>
        <a href="{{ route('admin.mitra.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection
