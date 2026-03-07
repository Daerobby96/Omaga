@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Detail Pengajuan Magang</h5>
                        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Status Badge --}}
                    <div class="mb-4">
                        @php
                            $statusColors = [
                                'diajukan' => 'bg-info',
                                'review_koordinator' => 'bg-warning',
                                'disetujui_koordinator' => 'bg-success',
                                'ditolak_koordinator' => 'bg-danger',
                                'review_mitra' => 'bg-warning',
                                'diterima_mitra' => 'bg-success',
                                'ditolak_mitra' => 'bg-danger',
                                'berjalan' => 'bg-primary',
                                'selesai' => 'bg-secondary',
                            ];
                            $statusLabels = [
                                'diajukan' => 'Menunggu Review',
                                'review_koordinator' => 'Review Koordinator',
                                'disetujui_koordinator' => 'Disetujui Koordinator',
                                'ditolak_koordinator' => 'Ditolak Koordinator',
                                'review_mitra' => 'Review Mitra',
                                'diterima_mitra' => 'Diterima Mitra',
                                'ditolak_mitra' => 'Ditolak Mitra',
                                'berjalan' => 'Sedang Berjalan',
                                'selesai' => 'Selesai',
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$pengajuan->status] ?? 'bg-secondary' }} fs-6">
                            {{ $statusLabels[$pengajuan->status] ?? ucfirst($pengajuan->status) }}
                        </span>
                    </div>

                    <div class="row">
                        {{-- Informasi Perusahaan Mitra --}}
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Perusahaan Mitra</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted ps-0" style="width:140px">Nama</td>
                                            <td class="fw-semibold">{{ $pengajuan->mitra->nama_perusahaan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Bidang</td>
                                            <td>{{ $pengajuan->mitra->bidang_usaha }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Alamat</td>
                                            <td>{{ $pengajuan->mitra->alamat }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Dosen Pembimbing --}}
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Dosen Pembimbing</h6>
                                </div>
                                <div class="card-body">
                                    @if($pengajuan->dosen)
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="text-muted ps-0" style="width:140px">Nama</td>
                                                <td class="fw-semibold">{{ $pengajuan->dosen->nama_lengkap }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted ps-0">Prodi</td>
                                                <td>{{ $pengajuan->dosen->program_studi }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted ps-0">Fakultas</td>
                                                <td>{{ $pengajuan->dosen->fakultas }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <p class="text-muted mb-0">Belum ada dosen pembimbing</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pengajuan --}}
                        <div class="col-12 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Detail Pengajuan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="text-muted ps-0" style="width:160px">Tanggal Mulai</td>
                                                    <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted ps-0">Tanggal Selesai</td>
                                                    <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted ps-0">Bidang Kerja</td>
                                                    <td>{{ $pengajuan->bidang_kerja }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="text-muted ps-0" style="width:160px">Deskripsi</td>
                                                    <td>{{ $pengajuan->deskripsi_pekerjaan ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted ps-0">Tanggal Pengajuan</td>
                                                    <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- File Lampiran --}}
                        @if($pengajuan->surat_pengantar || $pengajuan->proposal)
                        <div class="col-12 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Lampiran</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        @if($pengajuan->surat_pengantar)
                                            <a href="{{ route('mahasiswa.pengajuan.surat', $pengajuan) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-file-pdf me-2"></i>Surat Pengantar
                                            </a>
                                        @endif
                                        @if($pengajuan->proposal)
                                            <a href="{{ Storage::disk('public')->url($pengajuan->proposal) }}" class="btn btn-outline-secondary" target="_blank">
                                                <i class="fas fa-file-alt me-2"></i>Proposal
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Catatan --}}
                        @if($pengajuan->catatan_koordinator || $pengajuan->catatan_mitra)
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Catatan</h6>
                                </div>
                                <div class="card-body">
                                    @if($pengajuan->catatan_koordinator)
                                        <div class="mb-3">
                                            <span class="badge bg-warning mb-2">Catatan Koordinator</span>
                                            <p class="mb-0">{{ $pengajuan->catatan_koordinator }}</p>
                                        </div>
                                    @endif
                                    @if($pengajuan->catatan_mitra)
                                        <div>
                                            <span class="badge bg-info mb-2">Catatan Mitra</span>
                                            <p class="mb-0">{{ $pengajuan->catatan_mitra }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
