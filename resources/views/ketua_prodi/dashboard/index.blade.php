{{-- resources/views/ketua_prodi/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Ketua Prodi')
@section('page-title','Dashboard Ketua Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card" style="background: linear-gradient(135deg, #0ea472 0%, #059669 100%);">
            <div class="card-body text-white">
                <h4 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p class="mb-0">Dashboard Program Studi {{ $prodi }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users fa-lg text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Mahasiswa</div>
                        <div class="fs-4 fw-bold">{{ $totalMahasiswa }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-user-graduate fa-lg text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Mahasiswa Aktif</div>
                        <div class="fs-4 fw-bold">{{ $mahasiswaAktif }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-briefcase fa-lg text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Sedang Magang</div>
                        <div class="fs-4 fw-bold">{{ $pengajuanAktif }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-clipboard-list fa-lg text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Logbook Bulan Ini</div>
                        <div class="fs-4 fw-bold">{{ $logbookBulanIni }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Statistik Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Magang Aktif</span>
                    <span class="badge bg-success">{{ $pengajuanAktif }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Magang Selesai</span>
                    <span class="badge bg-primary">{{ $pengajuanSelesai }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Rata-rata Nilai</span>
                    <span class="fw-bold text-success">{{ $nilaiRataRata ? number_format($nilaiRataRata, 2) : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="fas fa-links me-2 text-primary"></i>Menu Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('ketua_prodi.mahasiswa.index') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-users mb-2 d-block"></i>
                            Mahasiswa
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('ketua_prodi.logbook.index') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-book mb-2 d-block"></i>
                            Logbook
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('ketua_prodi.pengajuan.index') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-file-signature mb-2 d-block"></i>
                            Pengajuan
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('ketua_prodi.nilai.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-star mb-2 d-block"></i>
                            Nilai
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
