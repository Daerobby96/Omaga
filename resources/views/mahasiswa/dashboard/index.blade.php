{{-- resources/views/mahasiswa/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title','Dashboard Mahasiswa')
@section('page-title','Dashboard')
@section('page-sub','Halo, ' . $mahasiswa->nama_lengkap . ' 👋')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value">{{ $stats['total_pengajuan'] }}</div>
            <div class="stat-label">Total Pengajuan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-spinner"></i></div>
            <div class="stat-value">{{ $stats['berjalan'] }}</div>
            <div class="stat-label">Magang Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card orange">
            <div class="stat-icon orange"><i class="fas fa-book-open"></i></div>
            <div class="stat-value">{{ $stats['total_logbook'] }}</div>
            <div class="stat-label">Total Logbook</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-top:3px solid #64748b;">
            <div class="stat-icon" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Magang Selesai</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">

        {{-- Status Magang Aktif --}}
        @if($pengajuanAktif)
        <div class="card mb-4" style="border-top:3px solid #0ea472;">
            <div class="card-header">
                <span class="card-header-title"><i class="fas fa-briefcase me-2 text-success"></i>Magang Sedang Berjalan</span>
                <span class="bdg {{ $pengajuanAktif->status_badge['class'] }}">{{ $pengajuanAktif->status_badge['label'] }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="font-size:18px;font-weight:800;color:#0f172a;">{{ $pengajuanAktif->mitra->nama_perusahaan }}</div>
                        <div style="font-size:13px;color:#64748b;">{{ $pengajuanAktif->bidang_kerja }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:4px;"><i class="fas fa-map-marker-alt me-1"></i>{{ $pengajuanAktif->mitra->alamat }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Dosen Pembimbing</div>
                        <div style="font-size:14px;font-weight:600;margin-top:3px;">{{ $pengajuanAktif->dosen?->nama_lengkap ?? '-' }}</div>
                        <div style="font-size:12px;color:#64748b;">{{ $pengajuanAktif->durasi }}</div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:12px;color:#64748b;">Progress Magang</span>
                            <span style="font-size:12px;font-weight:700;">{{ $pengajuanAktif->progress }}% · Sisa {{ $pengajuanAktif->sisa_hari }} hari</span>
                        </div>
                        <div class="prog-wrap" style="height:8px;">
                            <div class="prog-fill" style="background:#0ea472;width:{{ $pengajuanAktif->progress }}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('mahasiswa.logbook.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Isi Logbook Hari Ini</a>
                    <a href="{{ route('mahasiswa.pengajuan.show',$pengajuanAktif) }}" class="btn btn-outline-secondary btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-plus fa-3x mb-3" style="color:#cbd5e1;"></i>
                <h6 class="fw-bold">Belum Ada Magang Aktif</h6>
                <p class="text-muted" style="font-size:13px;">Ajukan magang ke perusahaan mitra yang tersedia</p>
                <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Ajukan Magang Sekarang
                </a>
            </div>
        </div>
        @endif

        {{-- Logbook Terbaru --}}
        @if($logbookTerbaru->count())
        <div class="card">
            <div class="card-header">
                <span class="card-header-title">Logbook Terbaru</span>
                <a href="{{ route('mahasiswa.logbook.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Jam</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($logbookTerbaru as $lb)
                        <tr>
                            <td style="font-size:12px;font-family:'DM Mono',mono;">{{ $lb->tanggal->format('d M Y') }}</td>
                            <td style="font-size:13px;">{{ Str::limit($lb->kegiatan, 60) }}</td>
                            <td style="font-size:12px;color:#64748b;">{{ substr($lb->jam_masuk,0,5) }} – {{ substr($lb->jam_keluar,0,5) }}</td>
                            <td><span class="bdg {{ $lb->status_badge['class'] }}">{{ $lb->status_badge['label'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Profil --}}
    <div class="col-xl-4">
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <div class="av av-lg mx-auto mb-3" style="background:#1a56db;">{{ $mahasiswa->avatar_initials }}</div>
                <div style="font-size:16px;font-weight:700;">{{ $mahasiswa->nama_lengkap }}</div>
                <div style="font-size:12px;color:#64748b;font-family:'DM Mono',mono;">{{ $mahasiswa->nim }}</div>
                <div style="font-size:13px;color:#64748b;margin-top:4px;">{{ $mahasiswa->program_studi }}</div>
                <div style="font-size:13px;margin-top:8px;">
                    <span class="bdg {{ $mahasiswa->status_badge['class'] }}">{{ $mahasiswa->status_badge['label'] }}</span>
                </div>
                <div class="row g-2 mt-3">
                    <div class="col-6 p-2 rounded-3" style="background:#f8fafc;">
                        <div style="font-size:18px;font-weight:800;color:#1a56db;">{{ number_format($mahasiswa->ipk,2) }}</div>
                        <div style="font-size:11px;color:#64748b;">IPK</div>
                    </div>
                    <div class="col-6 p-2 rounded-3" style="background:#f8fafc;">
                        <div style="font-size:18px;font-weight:800;color:#0ea472;">{{ $mahasiswa->semester }}</div>
                        <div style="font-size:11px;color:#64748b;">Semester</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Pengajuan --}}
        <div class="card">
            <div class="card-header"><span class="card-header-title">Riwayat Pengajuan</span></div>
            <div class="card-body" style="padding:12px 16px;">
                @forelse($pengajuan->take(5) as $p)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ Str::limit($p->mitra->nama_perusahaan,25) }}</div>
                        <div style="font-size:11px;color:#64748b;">{{ $p->created_at->format('M Y') }}</div>
                    </div>
                    <span class="bdg {{ $p->status_badge['class'] }}" style="font-size:10px;">{{ $p->status_badge['label'] }}</span>
                </div>
                @empty
                <div class="text-center py-3 text-muted" style="font-size:13px;">Belum ada riwayat</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
