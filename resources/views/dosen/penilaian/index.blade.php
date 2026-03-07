{{-- resources/views/dosen/penilaian/index.blade.php --}}
@extends('layouts.app')
@section('title','Penilaian')
@section('page-title','Daftar Penilaian Mahasiswa')
@section('page-sub','Kelola penilaian seluruh mahasiswa bimbingan')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Penilaian ({{ $penilaian->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Perusahaan Mitra</th>
                    <th>Nilai Dosen</th>
                    <th>Nilai Mitra</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaian as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $p->pengajuan->mitra->nama_perusahaan }}</td>
                    <td>
                        @if($p->dosenSudahNilai())
                            @php $nd = ($p->nilai_pembimbingan + $p->nilai_laporan + $p->nilai_seminar) / 3; @endphp
                            <span style="font-weight:700;color:#1a56db;">{{ number_format($nd,1) }}</span>
                        @else
                            <span class="bdg bg-warning-subtle text-warning">Belum Dinilai</span>
                        @endif
                    </td>
                    <td>
                        @if($p->mitraSudahNilai())
                            @php $nm = ($p->nilai_kedisiplinan + $p->nilai_kemampuan_teknis + $p->nilai_komunikasi + $p->nilai_inisiatif + $p->nilai_kerjasama) / 5; @endphp
                            <span style="font-weight:700;color:#0ea472;">{{ number_format($nm,1) }}</span>
                        @else
                            <span class="bdg bg-secondary-subtle text-secondary">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        @if($p->nilai_akhir)
                            <span style="font-size:16px;font-weight:800;color:{{ $p->lulus ? '#0ea472' : '#ef4444' }};">
                                {{ number_format($p->nilai_akhir,1) }}
                            </span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($p->grade)
                            @php $gc = ['A'=>'#0ea472','B'=>'#1a56db','C'=>'#f59e0b','D'=>'#f97316','E'=>'#ef4444'][$p->grade] ?? '#64748b'; @endphp
                            <span style="font-size:20px;font-weight:800;color:{{ $gc }};">{{ $p->grade }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if(!$p->dosenSudahNilai())
                        <a href="{{ route('dosen.penilaian.create',$p->pengajuan) }}" class="btn btn-sm btn-primary" style="font-size:11px;border-radius:7px;padding:4px 10px;">
                            <i class="fas fa-star me-1"></i>Nilai
                        </a>
                        @else
                        <a href="{{ route('dosen.penilaian.create',$p->pengajuan) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;border-radius:7px;padding:4px 10px;">
                            Lihat
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fas fa-star fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada data penilaian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penilaian->hasPages())
    <div class="card-body border-top">{{ $penilaian->links() }}</div>
    @endif
</div>
@endsection
