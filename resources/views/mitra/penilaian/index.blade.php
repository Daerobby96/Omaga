{{-- resources/views/mitra/penilaian/index.blade.php --}}
@extends('layouts.app')
@section('title','Penilaian')
@section('page-title','Riwayat Penilaian')
@section('page-sub','Lihat penilaian yang telah Anda berikan kepada mahasiswa')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-header-title">Riwayat Penilaian ({{ $penilaian->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Penilaian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->pengajuan->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->pengajuan->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;font-family:'DM Mono',mono;">{{ $p->pengajuan->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $p->pengajuan->mitra->nama_perusahaan }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->dinilai_mitra_at?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @if($p->lulus)
                            <span class="bdg bg-success-subtle text-success">Lulus</span>
                        @elseif($p->nilai_akhir)
                            <span class="bdg bg-danger-subtle text-danger">Tidak Lulus</span>
                        @else
                            <span class="bdg bg-warning-subtle text-warning">Menunggu Nilai Dosen</span>
                        @endif
                    </td>
                    <td>
                        @if($p->nilai_akhir)
                        <div style="font-weight:700;color:#0ea472;">{{ number_format($p->nilai_akhir,1) }}</div>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fas fa-star fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada penilaian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penilaian->hasPages())
    <div class="card-body border-top" style="padding:14px 20px;">
        {{ $penilaian->links() }}
    </div>
    @endif
</div>
@endsection
