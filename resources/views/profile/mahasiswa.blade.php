{{-- resources/views/profile/mahasiswa.blade.php --}}
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
    @if($user->mahasiswa)
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-id-card me-2 text-success"></i>Data Pribadi</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="{{ $user->mahasiswa->nim }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Program Studi</label>
                    <input type="text" class="form-control" value="{{ $user->mahasiswa->program_studi }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Angkatan</label>
                    <input type="text" class="form-control" value="{{ $user->mahasiswa->angkatan }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->mahasiswa->no_hp) }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap">{{ old('alamat', $user->mahasiswa->alamat) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- CV & Dokumen --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-file-upload me-2 text-warning"></i>CV & Dokumen</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">CV / Resume</label>
                    <input type="file" name="cv" class="form-control" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                    @if($user->mahasiswa->cv)
                        <a href="{{ Storage::url($user->mahasiswa->cv) }}" target="_blank" class="btn btn-sm mt-2" style="background:#10b981;color:#fff;">
                            <i class="fas fa-eye me-1"></i>Lihat CV Saat Ini
                        </a>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Transkrip Nilai</label>
                    <input type="file" name="transkrip" class="form-control" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                    @if($user->mahasiswa->transkrip)
                        <a href="{{ Storage::url($user->mahasiswa->transkrip) }}" target="_blank" class="btn btn-sm mt-2" style="background:#10b981;color:#fff;">
                            <i class="fas fa-eye me-1"></i>Lihat Transkrip Saat Ini
                        </a>
                    @endif
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
