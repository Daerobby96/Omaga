{{-- resources/views/admin/mitra/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Mitra')
@section('page-title','Detail Mitra')
@section('page-sub', $mitra->nama_perusahaan)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-header-title"><i class="fas fa-building me-2 text-primary"></i>Data Perusahaan</span>
        <span class="badge bg-{{ $mitra->status === 'aktif' ? 'success' : 'secondary' }}">
            {{ ucfirst($mitra->status) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" value="{{ $mitra->nama_perusahaan }}" readonly>
            </div>
            <div class="col-md-4">
                @if($mitra->logo)
                <img src="{{ asset('storage/'.$mitra->logo) }}" style="max-height:80px;" alt="Logo">
                @endif
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Bidang Usaha</label>
                <input type="text" class="form-control" value="{{ $mitra->bidang_usaha }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" value="{{ $mitra->alamat ?? '-' }}" readonly>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-user me-2 text-success"></i>Data Kontak</span></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kontak</label>
                <input type="text" class="form-control" value="{{ $mitra->nama_kontak }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan Kontak</label>
                <input type="text" class="form-control" value="{{ $mitra->jabatan_kontak ?? '-' }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $mitra->user->email }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. Telepon</label>
                <input type="text" class="form-control" value="{{ $mitra->no_telepon ?? '-' }}" readonly>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-users me-2 text-info"></i>Mahasiswa Magang ({{ $mitra->pengajuan->count() }})</span></div>
    <div class="card-body">
        @forelse($mitra->pengajuan as $p)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <div class="fw-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                <small class="text-muted">{{ $p->mahasiswa->nim }}</small>
            </div>
            <span class="badge bg-{{ $p->status == 'berjalan' ? 'primary' : ($p->status == 'selesai' ? 'success' : 'secondary') }}">
                {{ ucfirst($p->status) }}
            </span>
        </div>
        @empty
        <p class="text-muted mb-0">Belum ada mahasiswa magang</p>
        @endforelse
    </div>
</div>

<div class="d-flex gap-3">
    <a href="{{ route('admin.mitra.edit', $mitra->id) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    <form action="{{ route('admin.mitra.destroy', $mitra->id) }}" method="POST" class="d-inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus?')">
            <i class="fas fa-trash me-2"></i>Hapus
        </button>
    </form>
    <a href="{{ route('admin.mitra.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
</div>
</div>
@endsection
