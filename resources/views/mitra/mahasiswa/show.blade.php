{{-- resources/views/mitra/mahasiswa/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Mahasiswa')
@section('page-title','Detail Mahasiswa Magang')
@section('page-sub', $pengajuan->mahasiswa->nama_lengkap)

@section('content')
<div class="row g-4">
<div class="col-xl-8">

    {{-- Info Mahasiswa --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title">Informasi Mahasiswa</span>
            <span class="bdg {{ $pengajuan->status_badge['class'] }}">{{ $pengajuan->status_badge['label'] }}</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="av av-lg" style="background:#1a56db;">{{ $pengajuan->mahasiswa->avatar_initials }}</div>
                        <div>
                            <div style="font-size:16px;font-weight:700;">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                            <div style="font-size:12px;color:#64748b;font-family:'DM Mono',mono;">{{ $pengajuan->mahasiswa->nim }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mahasiswa->program_studi }}</div>
                            <div style="font-size:12px;color:#64748b;">IPK: <strong>{{ number_format($pengajuan->mahasiswa->ipk,2) }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Bidang Kerja</div>
                        <div style="font-size:14px;font-weight:600;">{{ $pengajuan->bidang_kerja }}</div>
                    </div>
                    <div class="mb-2">
                        <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Dosen Pembimbing</div>
                        <div style="font-size:14px;font-weight:600;">{{ $pengajuan->dosen?->nama_lengkap ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Periode</div>
                        <div style="font-size:14px;font-weight:600;">{{ $pengajuan->durasi }}</div>
                    </div>
                </div>
                @if($pengajuan->status === 'berjalan')
                <div class="col-12">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:12px;color:#64748b;">Progress</span>
                        <span style="font-size:12px;font-weight:700;">{{ $pengajuan->progress }}%</span>
                    </div>
                    <div class="prog-wrap" style="height:8px;">
                        <div class="prog-fill" style="background:#0ea472;width:{{ $pengajuan->progress }}%;"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Dokumen Mahasiswa --}}
    @if($pengajuan->mahasiswa->cv || $pengajuan->mahasiswa->transkrip)
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-file-alt me-2"></i>Dokumen Mahasiswa</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @if($pengajuan->mahasiswa->cv)
                <div class="col-md-6">
                    <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-file-pdf text-danger" style="font-size:20px;"></i>
                            <span style="font-weight:600;">CV / Resume</span>
                        </div>
                        <a href="{{ Storage::url($pengajuan->mahasiswa->cv) }}" target="_blank" class="btn btn-sm" style="background:#3b82f6;color:#fff;">
                            <i class="fas fa-eye me-1"></i>Lihat CV
                        </a>
                    </div>
                </div>
                @endif
                @if($pengajuan->mahasiswa->transkrip)
                <div class="col-md-6">
                    <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-file-pdf text-danger" style="font-size:20px;"></i>
                            <span style="font-weight:600;">Transkrip Nilai</span>
                        </div>
                        <a href="{{ Storage::url($pengajuan->mahasiswa->transkrip) }}" target="_blank" class="btn btn-sm" style="background:#3b82f6;color:#fff;">
                            <i class="fas fa-eye me-1"></i>Lihat Transkrip
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Logbook --}}
    <div class="card">
        <div class="card-header">
            <span class="card-header-title">Logbook Harian ({{ $pengajuan->logbook->count() }} Entri)</span>
        </div>
        @if($pengajuan->logbook->count())
        <div class="table-responsive">
            <table class="table-custom">
                <thead><tr><th>Tanggal</th><th>Jam Masuk</th><th>Jam Keluar</th><th>Kegiatan</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($pengajuan->logbook->sortByDesc('tanggal') as $lb)
                    <tr>
                        <td style="font-size:12px;font-family:'DM Mono',mono;color:#64748b;">{{ $lb->tanggal->format('d M Y') }}</td>
                        <td style="font-size:12px;color:#0ea472;font-weight:600;">{{ substr($lb->jam_masuk,0,5) }}</td>
                        <td style="font-size:12px;color:#ef4444;font-weight:600;">{{ substr($lb->jam_keluar,0,5) }}</td>
                        <td>
                            <div style="font-size:13px;">{{ Str::limit($lb->kegiatan, 80) }}</div>
                            @if($lb->catatan_mitra)
                                <div style="font-size:11px;color:#64748b;margin-top:2px;"><i class="fas fa-comment me-1"></i>{{ $lb->catatan_mitra }}</div>
                            @endif
                        </td>
                        <td><span class="bdg {{ $lb->status_badge['class'] }}">{{ $lb->status_badge['label'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="card-body text-center py-4 text-muted" style="font-size:13px;">Belum ada logbook</div>
        @endif
    </div>
</div>

<div class="col-xl-4">
    {{-- Statistik --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title">Statistik Kehadiran</span></div>
        <div class="card-body">
            @php
            $totalLb  = $pengajuan->logbook->count();
            $setujui  = $pengajuan->logbook->where('status','disetujui')->count();
            $pending  = $pengajuan->logbook->where('status','submitted')->count();
            @endphp
            <div class="row g-2 text-center">
                <div class="col-4 p-3 rounded-3" style="background:#ebf0ff;">
                    <div style="font-size:20px;font-weight:800;color:#1a56db;">{{ $totalLb }}</div>
                    <div style="font-size:11px;color:#64748b;">Total</div>
                </div>
                <div class="col-4 p-3 rounded-3" style="background:#e3f8ef;">
                    <div style="font-size:20px;font-weight:800;color:#0ea472;">{{ $setujui }}</div>
                    <div style="font-size:11px;color:#64748b;">Disetujui</div>
                </div>
                <div class="col-4 p-3 rounded-3" style="background:#fff8e6;">
                    <div style="font-size:20px;font-weight:800;color:#f59e0b;">{{ $pending }}</div>
                    <div style="font-size:11px;color:#64748b;">Pending</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aksi --}}
    @if($pengajuan->status === 'selesai' && !$pengajuan->penilaian?->mitraSudahNilai())
    <div class="card mb-4" style="border:1px solid #fde68a;">
        <div class="card-body text-center py-4">
            <i class="fas fa-star fa-2x text-warning mb-3 d-block"></i>
            <p style="font-size:13px;color:#64748b;">Magang selesai. Berikan penilaian untuk mahasiswa ini.</p>
            <a href="{{ route('mitra.penilaian.create',$pengajuan) }}" class="btn btn-warning w-100">
                <i class="fas fa-star me-2"></i>Beri Penilaian
            </a>
        </div>
    </div>
    @endif

    @if($pengajuan->penilaian?->mitraSudahNilai())
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title">Penilaian Anda</span></div>
        <div class="card-body">
            @php $p = $pengajuan->penilaian; @endphp
            @foreach([
                'Kedisiplinan'    => $p->nilai_kedisiplinan,
                'Kemampuan Teknis'=> $p->nilai_kemampuan_teknis,
                'Komunikasi'      => $p->nilai_komunikasi,
                'Inisiatif'       => $p->nilai_inisiatif,
                'Kerjasama'       => $p->nilai_kerjasama,
            ] as $label => $val)
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span style="font-size:12px;color:#64748b;">{{ $label }}</span>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:80px;"><div class="prog-wrap"><div class="prog-fill" style="background:#f59e0b;width:{{ $val }}%;"></div></div></div>
                    <span style="font-size:13px;font-weight:700;width:28px;text-align:right;">{{ $val }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div>
@endsection
