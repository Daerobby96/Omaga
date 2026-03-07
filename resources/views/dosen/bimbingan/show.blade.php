{{-- resources/views/dosen/bimbingan/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Bimbingan')
@section('page-title','Detail Bimbingan')
@section('page-sub','Detail mahasiswa bimbingan')

@section('content')
@php
$pengajuan = \App\Models\PengajuanMagang::with(['mahasiswa','mitra','dosen','logbook'=>fn($q)=>$q->orderByDesc('tanggal')->limit(10)])->findOrFail($pengajuan);
@endphp

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><span class="card-header-title">Data Mahasiswa</span></div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="av av-lg mx-auto" style="background:#1a56db;">{{ $pengajuan->mahasiswa->avatar_initials }}</div>
                    <h5 class="mt-2 mb-0">{{ $pengajuan->mahasiswa->nama_lengkap }}</h5>
                    <div class="text-muted" style="font-size:13px;">{{ $pengajuan->mahasiswa->nim }}</div>
                </div>
                <div class="row g-2">
                    <div class="col-6"><div class="p-2 rounded" style="background:#f8fafc;font-size:12px;"><strong>Prodi</strong><div>{{ $pengajuan->mahasiswa->program_studi }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#f8fafc;font-size:12px;"><strong>Angkatan</strong><div>{{ $pengajuan->mahasiswa->angkatan }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#f8fafc;font-size:12px;"><strong>Semester</strong><div>{{ $pengajuan->mahasiswa->semester }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#f8fafc;font-size:12px;"><strong>IPK</strong><div>{{ number_format($pengajuan->mahasiswa->ipk,2) }}</div></div></div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><span class="card-header-title">Data Mitra</span></div>
            <div class="card-body">
                <div class="mb-2"><strong>{{ $pengajuan->mitra->nama_perusahaan }}</strong></div>
                <div class="text-muted" style="font-size:12px;">{{ $pengajuan->mitra->bidang_usaha }}</div>
                <div class="mt-2" style="font-size:12px;"><i class="fas fa-map-marker-alt me-1"></i> {{ $pengajuan->mitra->alamat }}</div>
                <div style="font-size:12px;"><i class="fas fa-phone me-1"></i> {{ $pengajuan->mitra->telepon }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><span class="card-header-title">Detail Pengajuan</span></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:11px;">BIDANG KERJA</label>
                        <div style="font-weight:600;">{{ $pengajuan->bidang_kerja }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:11px;">PERIODE</label>
                        <div style="font-weight:600;">{{ $pengajuan->tanggal_mulai->format('d M Y') }} - {{ $pengajuan->tanggal_selesai->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:11px;">STATUS</label>
                        <div><span class="bdg {{ $pengajuan->status_badge['class'] }}">{{ $pengajuan->status_badge['label'] }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:11px;">PROGRESS</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1"><div class="prog-wrap"><div class="prog-fill" style="background:#1a56db;width:{{ $pengajuan->progress }}%"></div></div></div>
                            <span style="font-weight:600;">{{ $pengajuan->progress }}%</span>
                        </div>
                    </div>
                    @if($pengajuan->surat_pengantar)
                    <div class="col-12">
                        <label class="form-label text-muted" style="font-size:11px;">SURAT PENGANTAR</label>
                        <a href="{{ asset('storage/'.$pengajuan->surat_pengantar) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf me-1"></i>Lihat Surat</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="card-header-title">Logbook Terbaru</span>
                <a href="{{ route('dosen.logbook.index', ['mahasiswa_id' => $pengajuan->mahasiswa_id]) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Lihat Semua</a>
            </div>
            <div class="table-responsive" style="max-height:300px;overflow-y:auto;">
                <table class="table-custom table-sm">
                    <thead><tr><th>Tanggal</th><th>Activity</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($pengajuan->logbook as $log)
                        <tr>
                            <td style="font-size:12px;">{{ $log->tanggal->format('d M') }}</td>
                            <td style="font-size:12px;">{{ Str::limit($log->keterangan,50) }}</td>
                            <td><span class="bdg bdg-sm {{ $log->status === 'disetujui' ? 'bg-success-subtle text-success' : ($log->status === 'revisi' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') }}">{{ ucfirst($log->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada logbook</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pengajuan->penilaian)
        <div class="card mb-4">
            <div class="card-header"><span class="card-header-title">Nilai</span></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:11px;">NILAI AKHIR</label>
                        <div style="font-size:24px;font-weight:700;color:#1a56db;">{{ number_format($pengajuan->penilaian->nilai_akhir,1) }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:11px;">GRADE</label>
                        <div style="font-size:24px;font-weight:700;">{{ $pengajuan->penilaian->grade }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size:11px;">TANGGAL PENILAIAN</label>
                        <div style="font-size:14px;">{{ $pengajuan->penilaian->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="d-flex gap-2">
            <a href="{{ route('dosen.bimbingan.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Kembali</a>
            @if($pengajuan->status === 'berjalan')
            <a href="{{ route('dosen.penilaian.create',$pengajuan) }}" class="btn btn-primary px-4"><i class="fas fa-star me-2"></i>Beri Nilai</a>
            @endif
        </div>
    </div>
</div>
@endsection
