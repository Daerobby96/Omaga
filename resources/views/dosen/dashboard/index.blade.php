{{-- resources/views/dosen/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Dosen')
@section('page-title','Dashboard Dosen Pembimbing')
@section('page-sub','Halo, ' . auth()->user()->name)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue"><div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ $stats['total_bimbingan'] }}</div><div class="stat-label">Total Bimbingan</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green"><div class="stat-icon green"><i class="fas fa-spinner"></i></div>
            <div class="stat-value">{{ $stats['aktif'] }}</div><div class="stat-label">Bimbingan Aktif</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card orange"><div class="stat-icon orange"><i class="fas fa-book-open"></i></div>
            <div class="stat-value">{{ $logbook_pending->count() }}</div><div class="stat-label">Logbook Pending</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card red"><div class="stat-icon red"><i class="fas fa-star"></i></div>
            <div class="stat-value">{{ $stats['belum_dinilai'] }}</div><div class="stat-label">Belum Dinilai</div></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Mahasiswa Bimbingan</span>
                <a href="{{ route('dosen.bimbingan.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Mahasiswa</th><th>Mitra</th><th>Progress</th><th>Logbook</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($bimbingan->take(6) as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                                    <div><div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                    <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div></div>
                                </div>
                            </td>
                            <td style="font-size:12px;">{{ Str::limit($p->mitra->nama_perusahaan,20) }}</td>
                            <td style="width:100px;">
                                <div class="prog-wrap"><div class="prog-fill" style="background:#1a56db;width:{{ $p->progress }}%"></div></div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->progress }}%</div>
                            </td>
                            <td>
                                @php $pending = $p->logbook->where('status','submitted')->count(); @endphp
                                @if($pending)
                                    <span class="bdg bg-warning-subtle text-warning">{{ $pending }} pending</span>
                                @else
                                    <span class="bdg bg-secondary-subtle text-secondary">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dosen.bimbingan.show',$p) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 9px;">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada bimbingan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Logbook Menunggu Review</span>
                @if($logbook_pending->count())<span class="nav-badge red">{{ $logbook_pending->count() }}</span>@endif
            </div>
            <div class="card-body" style="padding:8px 0;">
                @forelse($logbook_pending as $lb)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div class="av av-sm" style="background:#f59e0b;">{{ $lb->mahasiswa->avatar_initials }}</div>
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:600;">{{ $lb->mahasiswa->nama_lengkap }}</div>
                        <div style="font-size:11px;color:#64748b;">{{ $lb->tanggal->locale('id')->translatedFormat('d F Y') }} · {{ Str::limit($lb->kegiatan,30) }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <form action="{{ route('dosen.logbook.setujui',$lb) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success" style="font-size:11px;border-radius:7px;padding:4px 8px;" title="Setujui">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted"><i class="fas fa-check-circle fa-2x mb-2 d-block opacity-25"></i>Semua logbook sudah direview</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
