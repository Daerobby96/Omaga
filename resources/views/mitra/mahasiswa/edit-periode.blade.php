{{-- resources/views/mitra/mahasiswa/edit-periode.blade.php --}}
@extends('layouts.app')
@section('title','Atur Periode Magang')
@section('page-title','Atur Periode Magang')
@section('page-sub', $pengajuan->mahasiswa->nama_lengkap)

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <span class="card-header-title"><i class="fas fa-calendar-alt me-2"></i>Periode Magang</span>
            </div>
            <div class="card-body">
                <form action="{{ route('mitra.mahasiswa.update-periode', $pengajuan) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control" 
                                   value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai?->format('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('tanggal_mulai')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" class="form-control" 
                                   value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai?->format('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('tanggal_selesai')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Periode
                        </button>
                        <a href="{{ route('mitra.mahasiswa.show', $pengajuan) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <span class="card-header-title"><i class="fas fa-info-circle me-2"></i>Informasi</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Mahasiswa</div>
                    <div style="font-size:14px;font-weight:600;">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">NIM</div>
                    <div style="font-size:14px;font-weight:600;">{{ $pengajuan->mahasiswa->nim }}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Bidang Kerja</div>
                    <div style="font-size:14px;font-weight:600;">{{ $pengajuan->bidang_kerja }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
