{{-- resources/views/admin/dosen/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Dosen')
@section('page-title','Detail Dosen')
@section('page-sub', $dosen->nama_lengkap)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-user me-2 text-primary"></i>Data Dosen</span></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ $dosen->nama_lengkap }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIDN</label>
                <input type="text" class="form-control" value="{{ $dosen->nidn }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $dosen->user->email }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" value="{{ $dosen->no_hp ?? '-' }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Program Studi</label>
                <input type="text" class="form-control" value="{{ $dosen->program_studi }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fakultas</label>
                <input type="text" class="form-control" value="{{ $dosen->fakultas }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Kuota Bimbingan</label>
                <input type="text" class="form-control" value="{{ $dosen->kuota_bimbingan }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan Fungsional</label>
                <input type="text" class="form-control" value="{{ $dosen->jabatan_fungsional ?? '-' }}" readonly>
            </div>
        </div>
        @if($dosen->is_ketua_prodi)
        <div class="row g-3 mt-2">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-star me-2"></i>
                    <strong>Ketua Program Studi {{ $dosen->prodi_yang_dikelola }}</strong>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-users me-2 text-success"></i>Mahasiswa Bimbingan ({{ $dosen->bimbingan->count() }})</span></div>
    <div class="card-body">
        @forelse($dosen->bimbingan as $b)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <div class="fw-semibold">{{ $b->mahasiswa->nama_lengkap }}</div>
                <small class="text-muted">{{ $b->mahasiswa->nim }} · {{ $b->mitra->nama_perusahaan }}</small>
            </div>
            <span class="badge bg-{{ $b->status == 'berjalan' ? 'primary' : ($b->status == 'selesai' ? 'success' : 'secondary') }}">
                {{ ucfirst($b->status) }}
            </span>
        </div>
        @empty
        <p class="text-muted mb-0">Belum ada mahasiswa bimbingan</p>
        @endforelse
    </div>
</div>

<div class="d-flex gap-3">
    <a href="{{ route('admin.dosen.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
</div>
</div>
@endsection
