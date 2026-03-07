{{-- resources/views/mahasiswa/pengajuan/index.blade.php --}}
@extends('layouts.app')
@section('title','Pengajuan Magang')
@section('page-title','Pengajuan Magang Saya')
@section('page-sub','Riwayat seluruh pengajuan magang')

@section('topbar-actions')
@php $adaAktif = auth()->user()->mahasiswa->pengajuanAktif; @endphp
@if(!$adaAktif)
<a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-plus"></i> Ajukan Magang Baru
</a>
@endif
@endsection

@section('content')
@if($pengajuan->isEmpty())
<div class="card">
    <div class="card-body text-center py-6">
        <i class="fas fa-file-alt fa-3x mb-3 d-block" style="color:#cbd5e1;"></i>
        <h6 class="fw-bold">Belum Ada Pengajuan</h6>
        <p class="text-muted" style="font-size:13px;">Ajukan magang ke perusahaan mitra yang tersedia</p>
        <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajukan Sekarang
        </a>
    </div>
</div>
@else
@foreach($pengajuan as $p)
<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-center g-3">
            <div class="col-md-5">
                <div style="font-size:11px;color:#94a3b8;font-family:'DM Mono',mono;margin-bottom:3px;">{{ $p->kode_pengajuan }}</div>
                <div style="font-size:16px;font-weight:700;">{{ $p->mitra->nama_perusahaan }}</div>
                <div style="font-size:13px;color:#64748b;">{{ $p->bidang_kerja }}</div>
                <div style="font-size:12px;color:#64748b;margin-top:3px;"><i class="fas fa-map-marker-alt me-1"></i>{{ $p->mitra->alamat }}</div>
            </div>
            <div class="col-md-3">
                <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;font-weight:600;">Periode</div>
                <div style="font-size:13px;font-weight:600;margin-top:2px;">{{ $p->durasi }}</div>
                @if($p->dosen)
                <div style="font-size:12px;color:#64748b;margin-top:4px;"><i class="fas fa-user-tie me-1"></i>{{ $p->dosen->nama_lengkap }}</div>
                @endif
            </div>
            <div class="col-md-2 text-center">
                <span class="bdg {{ $p->status_badge['class'] }}" style="font-size:12px;">{{ $p->status_badge['label'] }}</span>
                @if($p->status === 'berjalan')
                <div style="font-size:11px;color:#64748b;margin-top:5px;">{{ $p->progress }}% selesai</div>
                @endif
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('mahasiswa.pengajuan.show',$p) }}" class="btn btn-outline-primary btn-sm" style="border-radius:9px;">
                    <i class="fas fa-eye me-1"></i>Detail
                </a>
            </div>
        </div>

        @if(in_array($p->status,['ditolak_koordinator','ditolak_mitra']) && $p->catatan_koordinator || $p->catatan_mitra)
        <div class="mt-3 p-3 rounded-3" style="background:#fef2f2;border-left:3px solid #ef4444;">
            <div style="font-size:11px;color:#dc2626;font-weight:600;margin-bottom:2px;">ALASAN PENOLAKAN</div>
            <div style="font-size:13px;color:#7f1d1d;">{{ $p->catatan_koordinator ?? $p->catatan_mitra }}</div>
        </div>
        @endif
    </div>
</div>
@endforeach

@if($pengajuan->hasPages())
<div class="d-flex justify-content-center">{{ $pengajuan->links() }}</div>
@endif
@endif
@endsection
