{{-- resources/views/admin/sertifikat/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Nomor Sertifikat')
@section('page-title','Edit Nomor Sertifikat')
@section('page-sub','Ubah nomor sertifikat')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Nomor Sertifikat</div>
            <div class="card-body">
                <form action="{{ route('admin.sertifikat.update', $sertifikat) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $sertifikat->mahasiswa->nama_lengkap ?? '-' }} ({{ $sertifikat->mahasiswa->nim ?? '-' }})" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Sertifikat Lama</label>
                        <input type="text" class="form-control" value="{{ $sertifikat->nomor_sertifikat }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Sertifikat Baru <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_sertifikat" class="form-control @error('nomor_sertifikat') is-invalid @enderror" value="{{ old('nomor_sertifikat', $sertifikat->nomor_sertifikat) }}" required>
                        @error('nomor_sertifikat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Contoh: SERT/2026/0001</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Informasi Sertifikat</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width=\"150\">Nama Mahasiswa</th>
                        <td>{{ $sertifikat->mahasiswa->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td>{{ $sertifikat->mahasiswa->nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $sertifikat->mahasiswa->program_studi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mitra</th>
                        <td>{{ $sertifikat->pengajuan->mitra->nama_perusahaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Terbit</th>
                        <td>{{ $sertifikat->diterbitkan_at ? \Carbon\Carbon::parse($sertifikat->diterbitkan_at)->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status File</th>
                        <td>
                            @if($sertifikat->file_sertifikat)
                            <span class=\"badge bg-success\">Tersedia</span>
                            @else
                            <span class=\"badge bg-warning\">Belum Ada</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
