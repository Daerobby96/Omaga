{{-- resources/views/mitra/mahasiswa/index.blade.php --}}
@extends('layouts.app')
@section('title','Mahasiswa Magang')
@section('page-title','Mahasiswa Magang')
@section('page-sub','Kelola mahasiswa yang magang di perusahaan Anda')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Mahasiswa ({{ $pengajuan->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Bidang Kerja</th>
                    <th>Periode</th>
                    <th>Dosen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;font-family:'DM Mono',mono;">{{ $p->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $p->bidang_kerja }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->durasi }}</td>
                    <td style="font-size:13px;">{{ $p->dosen?->nama_lengkap ?? '-' }}</td>
                    <td><span class="bdg {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td>
                    <td>
                        <a href="{{ route('mitra.mahasiswa.show',$p) }}" class="btn btn-sm" style="background:#3b82f6;color:#fff;border:none;border-radius:7px;padding:4px 10px;font-size:11px;">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-user-graduate fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada mahasiswa magang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengajuan->hasPages())
    <div class="card-body border-top" style="padding:14px 20px;">
        {{ $pengajuan->links() }}
    </div>
    @endif
</div>
@endsection
