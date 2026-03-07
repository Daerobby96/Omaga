{{-- resources/views/profile/dosen.blade.php --}}
@extends('layouts.app')
@section('title','Profil Saya')
@section('page-title','Profil Saya')
@section('page-sub','Kelola informasi akun dan data pribadi Anda')

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

    {{-- Data Pribadi --}}
    @if($user->dosen)
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-id-card me-2 text-success"></i>Data Pribadi</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIP</label>
                    <input type="text" class="form-control" value="{{ $user->dosen->nip }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fakultas</label>
                    <input type="text" class="form-control" value="{{ $user->dosen->fakultas }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->dosen->no_hp) }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap">{{ old('alamat', $user->dosen->alamat) }}</textarea>
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
