{{-- resources/views/admin/sertifikat/index.blade.php --}}
@extends('layouts.app')
@section('title','Sertifikat')
@section('page-title','Manajemen Sertifikat')
@section('page-sub','Generate dan kelola sertifikat magang mahasiswa')

@section('topbar-actions')
<a href="{{ route('admin.sertifikat.settings') }}" class="btn btn-sm d-flex align-items-center gap-2" style="background:#0d9488;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;">
    <i class="fas fa-cog"></i> Pengaturan
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><span class="card-header-title">Daftar Sertifikat ({{ $sertifikat->total() }})</span></div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Nomor Sertifikat</th><th>Mahasiswa</th><th>Perusahaan</th><th>Tanggal Terbit</th><th>Diterbitkan Oleh</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($sertifikat as $s)
                <tr>
                    <td style="font-family:'DM Mono',mono;font-size:12px;color:#1a56db;font-weight:600;">{{ $s->nomor_sertifikat }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $s->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $s->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $s->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $s->pengajuan->mitra->nama_perusahaan }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $s->diterbitkan_at?->format('d M Y') ?? '—' }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $s->diterbitkanOleh?->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.sertifikat.view',$s) }}" target="_blank" class="btn btn-sm" style="background:#3b82f6;color:#fff;border:none;border-radius:7px;padding:4px 10px;font-size:11px;">
                            <i class="fas fa-eye me-1"></i>Preview
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-certificate fa-2x mb-2 d-block opacity-25"></i>Belum ada sertifikat
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sertifikat->hasPages())<div class="card-body border-top">{{ $sertifikat->links() }}</div>@endif
</div>

{{-- Mahasiswa yang bisa di-generate sertifikat --}}
@php
$bisaGenerate = \App\Models\PengajuanMagang::with(['mahasiswa','mitra'])
    ->where('status','selesai')
    ->whereHas('penilaian', fn($q) => $q->where('lulus',true))
    ->whereDoesntHave('sertifikat')
    ->latest()->take(10)->get();
@endphp
@if($bisaGenerate->count())
<div class="card mt-4" style="border:1px solid #fde68a;">
    <div class="card-header" style="background:#fefce8;border-bottom:1px solid #fde68a;">
        <span class="card-header-title" style="color:#92400e;"><i class="fas fa-exclamation-triangle me-2"></i>Siap Generate Sertifikat ({{ $bisaGenerate->count() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Mahasiswa</th><th>Mitra</th><th>Nilai Akhir</th><th>Grade</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($bisaGenerate as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#0ea472;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $p->mitra->nama_perusahaan }}</td>
                    <td style="font-size:15px;font-weight:700;color:#0ea472;">{{ number_format($p->penilaian->nilai_akhir,1) }}</td>
                    <td><span style="font-size:20px;font-weight:800;color:#0ea472;">{{ $p->penilaian->grade }}</span></td>
                    <td>
                        <form action="{{ route('admin.sertifikat.generate',$p) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-warning" style="font-size:11px;border-radius:7px;padding:4px 10px;">
                                <i class="fas fa-certificate me-1"></i>Generate
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
