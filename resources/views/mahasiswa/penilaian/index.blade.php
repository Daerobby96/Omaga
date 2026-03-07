{{-- resources/views/mahasiswa/penilaian/index.blade.php --}}
@extends('layouts.app')
@section('title','Nilai & Hasil')
@section('page-title','Nilai & Hasil Magang')
@section('page-sub','Lihat penilaian dari dosen dan perusahaan mitra')

@section('content')
@php
$mahasiswa = auth()->user()->mahasiswa;
$penilaianList = \App\Models\Penilaian::with(['pengajuan.mitra','pengajuan.sertifikat'])
    ->where('mahasiswa_id', $mahasiswa->id)->get();
@endphp

@forelse($penilaianList as $p)
<div class="card mb-4">
    <div class="card-header">
        <div>
            <div class="card-header-title">{{ $p->pengajuan->mitra->nama_perusahaan }}</div>
            <div style="font-size:12px;color:#64748b;">{{ $p->pengajuan->durasi }} · {{ $p->pengajuan->bidang_kerja }}</div>
        </div>
        @if($p->nilai_akhir)
        @php $gc = ['A'=>'#0ea472','B'=>'#1a56db','C'=>'#f59e0b','D'=>'#f97316','E'=>'#ef4444'][$p->grade] ?? '#64748b'; @endphp
        <div class="text-center">
            <div style="font-size:40px;font-weight:900;color:{{ $gc }};line-height:1;">{{ $p->grade }}</div>
            <div style="font-size:12px;color:#64748b;">{{ number_format($p->nilai_akhir,1) }}/100</div>
            <span class="bdg {{ $p->lulus ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                {{ $p->lulus ? 'LULUS' : 'TIDAK LULUS' }}
            </span>
        </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-4">
            {{-- Nilai Dosen --}}
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:28px;height:28px;border-radius:7px;background:#ebf0ff;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-chalkboard-teacher" style="font-size:12px;color:#1a56db;"></i>
                    </div>
                    <div style="font-size:14px;font-weight:700;">Penilaian Dosen Pembimbing</div>
                    <span class="bdg bg-primary-subtle text-primary ms-auto">Bobot 60%</span>
                </div>
                @if($p->dosenSudahNilai())
                @foreach(['Pembimbingan'=>$p->nilai_pembimbingan,'Laporan'=>$p->nilai_laporan,'Seminar'=>$p->nilai_seminar] as $label=>$val)
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:13px;color:#64748b;">{{ $label }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:100px;"><div class="prog-wrap"><div class="prog-fill" style="background:#1a56db;width:{{ $val }}%"></div></div></div>
                        <span style="font-size:14px;font-weight:700;width:32px;text-align:right;">{{ $val }}</span>
                    </div>
                </div>
                @endforeach
                @if($p->catatan_dosen)
                <div class="p-3 rounded-3 mt-3" style="background:#f8fafc;border-left:3px solid #1a56db;">
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-bottom:4px;">CATATAN DOSEN</div>
                    <div style="font-size:13px;">{{ $p->catatan_dosen }}</div>
                </div>
                @endif
                @else
                <div class="p-4 text-center rounded-3" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                    <i class="fas fa-clock text-muted mb-2 d-block"></i>
                    <div style="font-size:13px;color:#94a3b8;">Menunggu penilaian dosen</div>
                </div>
                @endif
            </div>

            {{-- Nilai Mitra --}}
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:28px;height:28px;border-radius:7px;background:#fff8e6;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-building" style="font-size:12px;color:#f59e0b;"></i>
                    </div>
                    <div style="font-size:14px;font-weight:700;">Penilaian Perusahaan</div>
                    <span class="bdg bg-warning-subtle text-warning ms-auto">Bobot 40%</span>
                </div>
                @if($p->mitraSudahNilai())
                @foreach(['Kedisiplinan'=>$p->nilai_kedisiplinan,'Kemampuan Teknis'=>$p->nilai_kemampuan_teknis,'Komunikasi'=>$p->nilai_komunikasi,'Inisiatif'=>$p->nilai_inisiatif,'Kerjasama'=>$p->nilai_kerjasama] as $label=>$val)
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:12px;color:#64748b;">{{ $label }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:80px;"><div class="prog-wrap"><div class="prog-fill" style="background:#f59e0b;width:{{ $val }}%"></div></div></div>
                        <span style="font-size:14px;font-weight:700;width:32px;text-align:right;">{{ $val }}</span>
                    </div>
                </div>
                @endforeach
                @if($p->catatan_mitra)
                <div class="p-3 rounded-3 mt-3" style="background:#f8fafc;border-left:3px solid #f59e0b;">
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-bottom:4px;">CATATAN MITRA</div>
                    <div style="font-size:13px;">{{ $p->catatan_mitra }}</div>
                </div>
                @endif
                @else
                <div class="p-4 text-center rounded-3" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                    <i class="fas fa-clock text-muted mb-2 d-block"></i>
                    <div style="font-size:13px;color:#94a3b8;">Menunggu penilaian mitra</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sertifikat --}}
        @if($p->pengajuan->sertifikat)
        <div class="mt-4 p-4 rounded-3 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#1a56db,#0ea472);">
            <i class="fas fa-certificate fa-2x text-white opacity-75"></i>
            <div class="flex-grow-1">
                <div style="font-size:14px;font-weight:700;color:white;">Sertifikat Tersedia</div>
                <div style="font-size:12px;color:rgba(255,255,255,.75);">{{ $p->pengajuan->sertifikat->nomor_sertifikat }}</div>
            </div>
            <a href="{{ route('mahasiswa.sertifikat.view',$p->pengajuan->sertifikat) }}" target="_blank" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-eye me-1"></i>Preview
            </a>
            <a href="{{ route('mahasiswa.sertifikat.download',$p->pengajuan->sertifikat) }}" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-download me-1"></i>Unduh
            </a>
        </div>
        @endif
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center py-6">
        <i class="fas fa-star fa-3x mb-3 d-block" style="color:#cbd5e1;"></i>
        <h6 class="fw-bold">Belum Ada Penilaian</h6>
        <p class="text-muted" style="font-size:13px;">Penilaian akan tersedia setelah magang selesai</p>
    </div>
</div>
@endforelse
@endsection


{{-- resources/views/mahasiswa/pengajuan/index.blade.php --}}
