{{-- resources/views/admin/prodi/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Program Studi')
@section('page-title','Detail Program Studi')
@section('page-sub', $prodi->nama_prodi)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-university me-2 text-primary"></i>Data Program Studi</span></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Program Studi</label>
                <input type="text" class="form-control" value="{{ $prodi->nama_prodi }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kode Prodi</label>
                <input type="text" class="form-control" value="{{ $prodi->kode_prodi }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Fakultas</label>
                <input type="text" class="form-control" value="{{ $prodi->fakultas }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenjang</label>
                <input type="text" class="form-control" value="{{ $prodi->jenjang }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Akreditasi</label>
                <input type="text" class="form-control" value="{{ $prodi->akreditasi ? $prodi->akreditasi . ' (' . $prodi->tahun_akreditasi . ')' : '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="{{ ucfirst($prodi->status) }}" readonly>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-primary">{{ $prodi->mahasiswa_count }}</div>
                <div class="text-muted">Mahasiswa</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-success">{{ $prodi->dosen_count }}</div>
                <div class="text-muted">Dosen</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-warning">{{ $prodi->pengajuan_count }}</div>
                <div class="text-muted">Pengajuan Magang</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-3 mt-4">
    <a href="{{ route('admin.prodi.edit', $prodi->id) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    <a href="{{ route('admin.prodi.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
</div>
</div>
@endsection
