{{-- resources/views/mitra/dashboard/edit-kuota.blade.php --}}
@extends('layouts.app')
@section('title','Edit Kuota Magang')
@section('page-title','Pengaturan Kuota')
@section('page-sub', $mitra->nama_perusahaan)

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">

        <div class="card mb-4">
            <div class="card-header">
                <span class="card-header-title"><i class="fas fa-chair me-2 text-primary"></i>Kuota Magang</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Atur jumlah maksimal mahasiswa magang yang dapat Anda terima.
                </p>

                {{-- Info Kuota Saat Ini --}}
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Kuota saat ini:</strong> {{ $mitra->kuota_magang }} mahasiswa<br>
                        <small>Sisa kuotanya: {{ $mitra->sisa_kuota }} mahasiswa</small>
                    </div>
                </div>

                <form action="{{ route('mitra.kuota.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">Jumlah Kuota Magang <span class="text-danger">*</span></label>
                        <input type="number" name="kuota_magang" class="form-control @error('kuota_magang') is-invalid @enderror" 
                            value="{{ old('kuota_magang', $mitra->kuota_magang) }}" min="0" max="100" required>
                        @error('kuota_magang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Minimal 0 (tidak menerima mahasiswa), maksimal 100 mahasiswa.
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('mitra.dashboard') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
