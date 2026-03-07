{{-- resources/views/mahasiswa/logbook/index.blade.php --}}
@extends('layouts.app')
@section('title','Logbook Harian')
@section('page-title','Logbook Harian')
@section('page-sub', 'Magang di ' . $pengajuanAktif->mitra->nama_perusahaan)

@section('topbar-actions')
<a href="{{ route('mahasiswa.logbook.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-plus"></i> Isi Logbook Hari Ini
</a>
@endsection

@section('content')
{{-- Summary Bar --}}
@php
$total   = $logbook->total();
$setujui = $logbook->getCollection()->where('status','disetujui')->count();
$submit  = $logbook->getCollection()->where('status','submitted')->count();
$revisi  = $logbook->getCollection()->where('status','revisi')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue"><div class="stat-icon blue"><i class="fas fa-book"></i></div>
            <div class="stat-value">{{ $logbook->total() }}</div><div class="stat-label">Total Logbook</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $logbook->getCollection()->where('status','disetujui')->count() }}</div><div class="stat-label">Disetujui</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card orange"><div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ $logbook->getCollection()->where('status','submitted')->count() }}</div><div class="stat-label">Menunggu Review</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card red"><div class="stat-icon red"><i class="fas fa-redo"></i></div>
            <div class="stat-value">{{ $logbook->getCollection()->where('status','revisi')->count() }}</div><div class="stat-label">Perlu Revisi</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-header-title">Riwayat Logbook</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr><th>Tanggal</th><th>Jam Kerja</th><th>Kegiatan</th><th>Durasi</th><th>Status</th><th>Catatan Dosen</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($logbook as $lb)
                <tr>
                    <td style="font-size:12px;font-family:'DM Mono',mono;color:#64748b;white-space:nowrap;">
                        {{ $lb->tanggal->format('d M Y') }}
                    </td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap;">
                        {{ substr($lb->jam_masuk,0,5) }} – {{ substr($lb->jam_keluar,0,5) }}
                    </td>
                    <td style="font-size:13px;max-width:300px;">
                        {{ Str::limit($lb->kegiatan, 80) }}
                    </td>
                    <td style="font-size:12px;color:#64748b;">{{ $lb->durasi_kerja ?? '—' }}</td>
                    <td><span class="bdg {{ $lb->status_badge['class'] }}">{{ $lb->status_badge['label'] }}</span></td>
                    <td>
                        @if($lb->catatan_dosen)
                            <span style="font-size:12px;color:#64748b;" title="{{ $lb->catatan_dosen }}">
                                {{ Str::limit($lb->catatan_dosen, 30) }}
                            </span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($lb->status, ['draft','revisi']))
                        <a href="{{ route('mahasiswa.logbook.edit',$lb) }}" class="btn btn-sm btn-outline-warning" style="font-size:11px;border-radius:7px;padding:4px 9px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        @else
                        <a href="{{ route('mahasiswa.logbook.show',$lb) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;border-radius:7px;padding:4px 9px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-book-open fa-2x mb-2 d-block opacity-25"></i>
                    Belum ada logbook. Mulai isi logbook harian Anda!
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logbook->hasPages())<div class="card-body border-top">{{ $logbook->links() }}</div>@endif
</div>
@endsection
