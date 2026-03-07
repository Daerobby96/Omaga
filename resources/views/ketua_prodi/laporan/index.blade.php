{{-- resources/views/ketua_prodi/laporan/index.blade.php --}}
@extends('layouts.app')
@section('title','Laporan Prodi')
@section('page-title','Laporan Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Status Pengajuan Magang</h5>
            </div>
            <div class="card-body">
                @forelse($pengajuanByStatus as $status)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ ucfirst($status->status) }}</span>
                    <span class="badge bg-primary">{{ $status->total }}</span>
                </div>
                @empty
                <p class="text-muted">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Mahasiswa per Angkatan</h5>
            </div>
            <div class="card-body">
                @forelse($mahasiswaByAngkatan as $mhs)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Angkatan {{ $mhs->angkatan }}</span>
                    <span class="badge bg-success">{{ $mhs->total }}</span>
                </div>
                @empty
                <p class="text-muted">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
