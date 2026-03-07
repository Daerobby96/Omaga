{{-- resources/views/ketua_prodi/bimbingan/index.blade.php --}}
@extends('layouts.app')
@section('title','Mahasiswa Bimbingan')
@section('page-title','Mahasiswa Bimbingan')
@section('page-sub', auth()->user()->dosen->nama_lengkap)

@section('content')
<div class="row">
<div class="col-xl-10">

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-header-title">Daftar Mahasiswa Bimbingan</span>
        <span class="badge bg-primary">{{ $bimbingan->total() }} mahasiswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mitra</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bimbingan as $p)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $p->mahasiswa->nama_lengkap }}</div>
                            <small class="text-muted">{{ $p->mahasiswa->nim }}</small>
                        </td>
                        <td>{{ $p->mitra->nama_perusahaan ?? '-' }}</td>
                        <td>
                            {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') : '-' }}
                            @if($p->tanggal_selesai)
                                <span class="text-muted">-</span>
                                {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                            @endif
                        </td>
                        <td>
                            @switch($p->status)
                                @case('berjalan')
                                    <span class="bdg bg-primary text-white">Berjalan</span>
                                    @break
                                @case('diterima_mitra')
                                    <span class="bdg bg-info text-white">Diterima</span>
                                    @break
                                @case('selesai')
                                    <span class="bdg bg-success text-white">Selesai</span>
                                    @break
                                @default
                                    <span class="bdg bg-secondary text-white">{{ ucfirst($p->status) }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('dosen.bimbingan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-user-graduate mb-2" style="font-size:24px;"></i>
                            <div>Belum ada mahasiswa bimbingan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bimbingan->hasPages())
    <div class="card-footer">
        {{ $bimbingan->links() }}
    </div>
    @endif
</div>

</div>
</div>
@endsection
