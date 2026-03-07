{{-- resources/views/profile/mitra.blade.php --}}
@extends('layouts.app')
@section('title','Profil Saya')
@section('page-title','Profil Saya')
@section('page-sub','Kelola informasi akun dan data perusahaan Anda')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Data Akun --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-user-circle me-2 text-primary"></i>Data Akun</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                </div>
            </div>
        </div>
    </div>

    {{-- Data Perusahaan --}}
    @if($user->mitra)
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-building me-2 text-warning"></i>Data Perusahaan</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" class="form-control" value="{{ $user->mitra->nama_perusahaan }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bidang Industri</label>
                    <input type="text" class="form-control" value="{{ $user->mitra->bidang_industri }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $user->mitra->telepon) }}" placeholder="02x-xxxxxxxx">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap perusahaan">{{ old('alamat', $user->mitra->alamat) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Logo Perusahaan</label>
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-grow-1">
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/jpeg,image/png">
                            <small class="text-muted">Format: JPG, PNG. Max 1MB. Akan ditampilkan di sertifikat.</small>
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if($user->mitra->logo && Storage::disk('public')->exists($user->mitra->logo))
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $user->mitra->logo) }}" alt="Logo" style="height:60px;object-fit:contain;" class="border rounded p-1">
                            <br><small class="text-muted">Tersimpan</small>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Tanda Tangan</label>
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-grow-1">
                            <input type="file" name="tanda_tangan" class="form-control @error('tanda_tangan') is-invalid @enderror" accept="image/jpeg,image/png">
                            <small class="text-muted">Format: JPG, PNG. Max 1MB. Akan ditampilkan di sertifikat.</small>
                            @error('tanda_tangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if($user->mitra->tanda_tangan && Storage::disk('public')->exists($user->mitra->tanda_tangan))
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $user->mitra->tanda_tangan) }}" alt="Tanda Tangan" style="height:60px;object-fit:contain;" class="border rounded p-1">
                            <br><small class="text-muted">Tersimpan</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Simpan Perubahan
        </button>
        <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
            Kembali
        </a>
    </div>
</form>
</div>
</div>
@endsection
