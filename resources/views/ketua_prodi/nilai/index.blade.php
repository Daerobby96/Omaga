{{-- resources/views/ketua_prodi/nilai/index.blade.php --}}
@extends('layouts.app')
@section('title','Nilai Prodi')
@section('page-title','Nilai Mahasiswa Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-star me-2 text-info"></i>Nilai Mahasiswa {{ $prodi }}</h5>
        <span class="badge bg-info">{{ $penilaian->total() }} records</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mitra</th>
                        <th>Nilai Dosen</th>
                        <th>Nilai Mitra</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilaian as $n)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $n->pengajuan->mahasiswa->nama_lengkap }}</div>
                            <small class="text-muted">{{ $n->pengajuan->mahasiswa->nim }}</small>
                        </td>
                        <td>{{ $n->pengajuan->mitra->nama_perusahaan }}</td>
                        <td>{{ $n->nilai_akhir ? number_format($n->nilai_akhir, 2) : '-' }}</td>
                        <td>{{ $n->nilai_kedisiplinan ? number_format(($n->nilai_kedisiplinan + $n->nilai_kemampuan_teknis + $n->nilai_komunikasi + $n->nilai_inisiatif + $n->nilai_kerjasama)/5, 2) : '-' }}</td>
                        <td class="fw-bold">{{ $n->nilai_akhir ? number_format($n->nilai_akhir, 2) : '-' }}</td>
                        <td>
                            @if($n->grade)
                            <span class="badge bg-{{ $n->grade == 'A' ? 'success' : ($n->grade == 'B' ? 'primary' : ($n->grade == 'C' ? 'warning' : 'danger')) }}">
                                {{ $n->grade }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($n->lulus)
                            <span class="badge bg-success">Lulus</span>
                            @else
                            <span class="badge bg-secondary">Belum Dinilai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data penilaian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $penilaian->links() }}
    </div>
</div>
@endsection
