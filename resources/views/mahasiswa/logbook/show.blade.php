{{-- resources/views/mahasiswa/logbook/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Logbook')
@section('page-title','Detail Logbook Harian')
@section('page-sub', $logbook->pengajuan->mitra->nama_perusahaan . ' · ' . \Carbon\Carbon::parse($logbook->tanggal)->locale('id')->translatedFormat('d F Y'))

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-3" style="background:#e3f8ef;border:1px solid #a7f3d0;">
    <div class="card-body d-flex align-items-center gap-3 py-3">
        <div class="av" style="background:#0ea472;"><i class="fas fa-briefcase" style="font-size:14px;color:white;"></i></div>
        <div>
            <div style="font-size:14px;font-weight:700;color:#065f46;">{{ $logbook->pengajuan->mitra->nama_perusahaan }}</div>
            <div style="font-size:12px;color:#047857;">Dosen: {{ $logbook->pengajuan->dosen?->nama_lengkap ?? '-' }} · {{ $logbook->pengajuan->bidang_kerja }}</div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-header-title"><i class="fas fa-info-circle me-2 text-info"></i>Status Logbook</span>
        @php
            $badge = $logbook->status_badge;
        @endphp
        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
    </div>
    <div class="card-body">
        @if($logbook->catatan_dosen)
        <div class="alert alert-info mb-0">
            <strong>Catatan Dosen:</strong> {{ $logbook->catatan_dosen }}
        </div>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-clock me-2 text-primary"></i>Kehadiran</span></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($logbook->tanggal)->locale('id')->translatedFormat('d F Y') }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Masuk</label>
                <input type="text" class="form-control" value="{{ $logbook->jam_masuk }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Keluar</label>
                <input type="text" class="form-control" value="{{ $logbook->jam_keluar }}" readonly>
            </div>
        </div>
        @if($logbook->durasi_kerja)
        <div class="mt-2" style="font-size:13px;color:#0ea472;">
            <i class="fas fa-check-circle me-1"></i>Durasi kerja: <strong>{{ $logbook->durasi_kerja }}</strong>
        </div>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><span class="card-header-title"><i class="fas fa-tasks me-2 text-success"></i>Aktivitas Kerja</span></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Kegiatan yang Dilakukan</label>
            <textarea class="form-control" rows="5" readonly>{{ $logbook->kegiatannya }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Hasil / Output</label>
            <textarea class="form-control" rows="3" readonly>{{ $logbook->hasil ?? '-' }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kendala (jika ada)</label>
            <textarea class="form-control" rows="2" readonly>{{ $logbook->kendala ?? '-' }}</textarea>
        </div>
        @if($logbook->foto_kegiatannya)
        <div>
            <label class="form-label">Foto Kegiatan</label>
            <div>
                <img src="{{ asset('storage/'.$logbook->foto_kegiatannya) }}" class="rounded" style="max-width:100%;max-height:400px;" alt="Foto kegiatan">
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex gap-3">
    @if(in_array($logbook->status, ['draft', 'revisi']))
    <a href="{{ route('mahasiswa.logbook.edit', $logbook->id) }}" class="btn btn-primary px-4">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    @endif
    <a href="{{ route('mahasiswa.logbook.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
</div>
</div>
@endsection
