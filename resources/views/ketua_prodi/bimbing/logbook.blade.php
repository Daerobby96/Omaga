{{-- resources/views/ketua_prodi/bimbing/logbook.blade.php --}}
@extends('layouts.app')
@section('title','Logbook Mahasiswa Bimbing')
@section('page-title','Review Logbook')
@section('page-sub','Mahasiswa Bimbing')

@section('content')
<div class="row">
<div class="col-xl-10">

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-header-title">Logbook Mahasiswa Bimbing</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mahasiswa</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logbook as $l)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $l->pengajuan->mahasiswa->nama_lengkap }}</div>
                            <small class="text-muted">{{ $l->pengajuan->mahasiswa->nim }}</small>
                        </td>
                        <td>{{ Str::limit($l->keterangan, 80) }}</td>
                        <td>
                            @switch($l->status)
                                @case('submitted')
                                    <span class="bdg bg-warning text-dark">Menunggu</span>
                                    @break
                                @case('approved')
                                    <span class="bdg bg-success">Disetujui</span>
                                    @break
                                @case('revision')
                                    <span class="bdg bg-danger">Revisi</span>
                                    @break
                                @default
                                    <span class="bdg bg-secondary">{{ $l->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($l->status == 'submitted')
                            <form action="{{ route('ketua_prodi.bimbing.logbook.setuju', $l->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Setuju">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revisiModal{{ $l->id }}">
                                <i class="fas fa-undo"></i>
                            </button>
                            @endif
                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $l->id }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-book-open mb-2" style="font-size:24px;"></i>
                            <div>Belum ada logbook</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logbook->hasPages())
    <div class="card-footer">
        {{ $logbook->links() }}
    </div>
    @endif
</div>

</div>
</div>

{{-- Modal Detail --}}
@foreach($logbook as $l)
<div class="modal fade" id="detailModal{{ $l->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Logbook</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Mahasiswa:</strong> {{ $l->pengajuan->mahasiswa->nama_lengkap }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</p>
                <p><strong>Jam:</strong> {{ $l->jam_mulai }} - {{ $l->jam_selesai }}</p>
                <p><strong>Keterangan:</strong></p>
                <p class="border rounded p-2">{{ $l->keterangan }}</p>
                @if($l->catatan)
                <p><strong>Catatan:</strong></p>
                <p class="border rounded p-2 bg-light">{{ $l->catatan }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="revisiModal{{ $l->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kembalikan untuk Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('ketua_prodi.bimbing.logbook.revisi', $l->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="catatan" class="form-control" rows="3" required placeholder="Masukkan catatan untuk revisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
