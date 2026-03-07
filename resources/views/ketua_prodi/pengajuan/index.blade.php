{{-- resources/views/ketua_prodi/pengajuan/index.blade.php --}}
@extends('layouts.app')
@section('title','Pengajuan Magang Prodi')
@section('page-title','Pengajuan Magang Mahasiswa Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-signature me-2 text-warning"></i>Pengajuan Magang {{ $prodi }}</h5>
        <span class="badge bg-warning">{{ $pengajuan->total() }} pengajuan</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mitra</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $p)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                            <small class="text-muted">{{ $p->mahasiswa->nim }}</small>
                        </td>
                        <td>
                            <div>{{ $p->mitra->nama_perusahaan }}</div>
                            <small class="text-muted">{{ $p->bidang_kerja }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->created_at)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>
                            @switch($p->status)
                                @case('menunggu')
                                    <span class="badge bg-secondary">Menunggu</span>
                                    @break
                                @case('diterima_dosen')
                                    <span class="badge bg-info">Diterima Dosen</span>
                                    @break
                                @case('ditolak_dosen')
                                    <span class="badge bg-danger">Ditolak Dosen</span>
                                    @break
                                @case('diterima_mitra')
                                    <span class="badge bg-success">Diterima Mitra</span>
                                    @break
                                @case('ditolak_mitra')
                                    <span class="badge bg-danger">Ditolak Mitra</span>
                                    @break
                                @case('berjalan')
                                    <span class="badge bg-primary">Berjalan</span>
                                    @break
                                @case('selesai')
                                    <span class="badge bg-success">Selesai</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada data pengajuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $pengajuan->links() }}
    </div>
</div>
@endsection
