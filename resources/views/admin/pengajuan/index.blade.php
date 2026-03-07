{{-- resources/views/admin/pengajuan/index.blade.php --}}
@extends('layouts.app')
@section('title','Pengajuan Magang')
@section('page-title','Pengajuan Magang')
@section('page-sub','Kelola seluruh pengajuan magang mahasiswa')

@section('content')
{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama mahasiswa / NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $s)
                        <option value="{{ $s }}" @selected(request('status')===$s)>
                            {{ \App\Models\PengajuanMagang::STATUS_LABELS[$s]['label'] ?? $s }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-1"><a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times"></i></a></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Pengajuan ({{ $pengajuan->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Kode</th><th>Mahasiswa</th><th>Perusahaan Mitra</th>
                    <th>Bidang Kerja</th><th>Periode</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $p)
                <tr>
                    <td style="font-family:'DM Mono',mono;font-size:12px;color:#64748b;">{{ $p->kode_pengajuan }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:500;">{{ $p->mitra->nama_perusahaan }}</td>
                    <td style="font-size:13px;">{{ $p->bidang_kerja }}</td>
                    <td style="font-size:11.5px;color:#64748b;">{{ $p->durasi }}</td>
                    <td><span class="bdg {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td>
                    <td>
                        <a href="{{ route('admin.pengajuan.show',$p) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 10px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada pengajuan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengajuan->hasPages())
    <div class="card-body border-top">{{ $pengajuan->links() }}</div>
    @endif
</div>
@endsection
