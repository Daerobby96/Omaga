{{-- resources/views/mitra/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Mitra')
@section('page-title','Dashboard')
@section('page-sub', $mitra->nama_perusahaan)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Statistik</h5>
    <a href="{{ route('mitra.kuota.edit') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-cog me-1"></i>Atur Kuota
    </a>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue"><div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Pengajuan</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card orange"><div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ $stats['review'] }}</div><div class="stat-label">Perlu Direview</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green"><div class="stat-icon green"><i class="fas fa-spinner"></i></div>
            <div class="stat-value">{{ $stats['aktif'] }}</div><div class="stat-label">Sedang Magang</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #8b5cf6;">
            <div class="stat-icon" style="background:#f3f0ff;color:#8b5cf6;"><i class="fas fa-chair"></i></div>
            <div class="stat-value">{{ $stats['sisa_kuota'] }}</div><div class="stat-label">Sisa Kuota ({{ $stats['kuota_magang'] }})</div></div>
    </div>
</div>

{{-- Permohonan Menunggu Review --}}
@php $reviewList = $pengajuan->whereIn('status',['disetujui_koordinator']); @endphp

{{-- Card Stats --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Statistik</h5>
    <a href="{{ route('mitra.kuota.edit') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-cog me-1"></i>Atur Kuota
    </a>
</div>
@if($reviewList->count())
<div class="card mb-4" style="border:1px solid #fde68a;background:#fffbeb;">
    <div class="card-header" style="background:#fef9c3;border-bottom:1px solid #fde68a;">
        <span class="card-header-title" style="color:#92400e;"><i class="fas fa-bell me-2"></i>Permohonan Magang Menunggu Keputusan Anda</span>
        <span class="nav-badge warn">{{ $reviewList->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Mahasiswa</th><th>Prodi</th><th>Bidang Kerja</th><th>Periode</th><th>Dokumen</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($reviewList as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">IPK {{ number_format($p->mahasiswa->ipk,2) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->mahasiswa->program_studi }}</td>
                    <td style="font-size:13px;">{{ $p->bidang_kerja }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->durasi }}</td>
                    <td>
                        @if(in_array($p->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']))
                        <a href="{{ route('mitra.surat.pengantar.preview', $p) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:11px;border-radius:7px;">
                            <i class="fas fa-file-pdf me-1 text-danger"></i>Surat
                        </a>
                        @else
                        <span class="text-muted" style="font-size:11px;">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if(in_array($p->status, ['disetujui_koordinator']))
                            <a href="{{ route('mitra.mahasiswa.edit-periode', $p) }}" class="btn btn-primary btn-sm" style="font-size:11px;border-radius:7px;padding:4px 10px;">
                                <i class="fas fa-calendar me-1"></i>Atur Periode
                            </a>
                            @endif
                            @if($p->status === 'disetujui_koordinator')
                            <form action="{{ route('mitra.mahasiswa.terima',$p) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm" style="font-size:11px;border-radius:7px;padding:4px 10px;">
                                    <i class="fas fa-check me-1"></i>Terima
                                </button>
                            </form>
                            <button class="btn btn-danger btn-sm" style="font-size:11px;border-radius:7px;padding:4px 10px;"
                                data-bs-toggle="modal" data-bs-target="#tolakModal{{ $p->id }}">
                                <i class="fas fa-times me-1"></i>Tolak
                            </button>
                        </div>
                        {{-- Modal Tolak --}}
                        <div class="modal fade" id="tolakModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header border-0"><h6 class="fw-bold">Tolak Pengajuan</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <form action="{{ route('mitra.mahasiswa.tolak',$p) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="modal-body pt-0">
                                            <p style="font-size:13px;color:#64748b;">Tolak pengajuan dari <strong>{{ $p->mahasiswa->nama_lengkap }}</strong>?</p>
                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="catatan_mitra" class="form-control" rows="3" required placeholder="Jelaskan alasan penolakan..."></textarea>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak Pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Mahasiswa Aktif --}}
<div class="card">
    <div class="card-header">
        <span class="card-header-title">Mahasiswa Sedang Magang</span>
        <span class="bdg bg-success-subtle text-success">{{ $mahasiswaAktif->count() }} Aktif</span>
    </div>
    @if($mahasiswaAktif->count())
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Mahasiswa</th><th>Prodi</th><th>Bidang Kerja</th><th>Dosen Pembimbing</th><th>Progress</th><th>Logbook</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($mahasiswaAktif as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#0ea472;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->mahasiswa->program_studi }}</td>
                    <td style="font-size:13px;">{{ $p->bidang_kerja }}</td>
                    <td style="font-size:12px;">{{ $p->dosen?->nama_lengkap ?? '—' }}</td>
                    <td style="width:100px;">
                        <div class="prog-wrap mb-1"><div class="prog-fill" style="background:#0ea472;width:{{ $p->progress }}%"></div></div>
                        <div style="font-size:11px;color:#64748b;">{{ $p->progress }}% · Sisa {{ $p->sisa_hari }}h</div>
                    </td>
                    <td>
                        <span style="font-size:13px;font-weight:700;">{{ $p->logbook->count() }}</span>
                        <span style="font-size:11px;color:#64748b;"> entri</span>
                    </td>
                    <td>
                        <a href="{{ route('mitra.mahasiswa.show',$p) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 10px;">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-user-graduate fa-2x mb-2 d-block opacity-25"></i>
        Belum ada mahasiswa aktif saat ini
    </div>
    @endif
</div>
@endsection
