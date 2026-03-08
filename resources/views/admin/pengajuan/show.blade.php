{{-- resources/views/admin/pengajuan/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Pengajuan')
@section('page-title','Detail Pengajuan Magang')
@section('page-sub',$pengajuan->kode_pengajuan)

@section('content')
<div class="row g-4">

{{-- Kolom Kiri --}}
<div class="col-xl-8">

    {{-- Info Utama --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title">Informasi Pengajuan</span>
            <span class="bdg {{ $pengajuan->status_badge['class'] }} fs-6">{{ $pengajuan->status_badge['label'] }}</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Mahasiswa</label>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div class="av av-lg" style="background:#1a56db;">{{ $pengajuan->mahasiswa->avatar_initials }}</div>
                        <div>
                            <div style="font-size:15px;font-weight:700;">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
                            <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mahasiswa->nim }} · {{ $pengajuan->mahasiswa->program_studi }}</div>
                            <div style="font-size:12px;color:#64748b;">IPK {{ number_format($pengajuan->mahasiswa->ipk,2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Perusahaan Mitra</label>
                    <div style="font-size:15px;font-weight:700;margin-top:4px;">{{ $pengajuan->mitra->nama_perusahaan }}</div>
                    <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mitra->bidang_usaha }}</div>
                    <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mitra->alamat }}</div>
                </div>
                <div class="col-md-4">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Bidang Kerja</label>
                    <div style="font-size:14px;font-weight:600;margin-top:4px;">{{ $pengajuan->bidang_kerja }}</div>
                </div>
                <div class="col-md-4">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Periode</label>
                    <div style="font-size:14px;font-weight:600;margin-top:4px;">{{ $pengajuan->durasi }}</div>
                </div>
                <div class="col-md-4">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Dosen Pembimbing</label>
                    <div style="font-size:14px;font-weight:600;margin-top:4px;">{{ $pengajuan->dosen?->nama_lengkap ?? '— Belum ditentukan —' }}</div>
                </div>
                @if($pengajuan->deskripsi_pekerjaan)
                <div class="col-12">
                    <label style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Deskripsi Pekerjaan</label>
                    <p style="font-size:13.5px;margin-top:4px;margin-bottom:0;">{{ $pengajuan->deskripsi_pekerjaan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Dokumen --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title">Dokumen & Nomor Surat</span></div>
        <div class="card-body">
            {{-- Form Edit Nomor Surat --}}
            @if(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']))
            <div class="mb-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <form action="{{ route('admin.pengajuan.update-nomor-surat', $pengajuan) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-2">
                        <label class="mb-0" style="font-size:13px;font-weight:600;white-space:nowrap;">Nomor Surat:</label>
                        <input type="text" name="nomor_surat" class="form-control form-control-sm" 
                               style="max-width:300px;" value="{{ $pengajuan->nomor_surat ?? '' }}" 
                               placeholder="Contoh: 001/SPM-TRPL/I/2024">
                        <button type="submit" class="btn btn-sm btn-primary px-3">Simpan</button>
                    </div>
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Kosongkan untuk menghasilkan nomor otomatis</small>
                </form>
            </div>
            @endif

            <div class="d-flex flex-wrap gap-2">
                @if(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']))
                <a href="{{ route('admin.surat.pengantar.preview', $pengajuan) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2" style="border-radius:10px;">
                    <i class="fas fa-file-pdf text-danger"></i> Surat Pengantar
                </a>
                <a href="{{ route('admin.surat.pengantar', $pengajuan) }}" class="btn btn-outline-success d-inline-flex align-items-center gap-2" style="border-radius:10px;">
                    <i class="fas fa-download"></i> Download
                </a>
                @else
                <span class="text-muted" style="font-size:13px;"><i class="fas fa-info-circle me-1"></i>Surat pengantar akan tersedia setelah pengajuan disetujui</span>
                @endif
                
                @if($pengajuan->proposal)
                <a href="{{ Storage::url($pengajuan->proposal) }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:10px;">
                    <i class="fas fa-file-pdf text-danger"></i> Proposal
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Magang --}}
    @if($pengajuan->status === 'berjalan')
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title">Progress Magang</span></div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <span style="font-size:13px;">{{ $pengajuan->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}</span>
                <span style="font-size:13px;font-weight:700;">{{ $pengajuan->progress }}%</span>
                <span style="font-size:13px;">{{ $pengajuan->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}</span>
            </div>
            <div class="prog-wrap" style="height:10px;">
                <div class="prog-fill" style="background:#1a56db;width:{{ $pengajuan->progress }}%;transition:width 1s;"></div>
            </div>
            <div class="text-center mt-2" style="font-size:13px;color:#64748b;">Sisa {{ $pengajuan->sisa_hari }} hari</div>

            <div class="row g-3 mt-2">
                <div class="col-4 text-center p-3 rounded-3" style="background:#ebf0ff;">
                    <div style="font-size:22px;font-weight:800;color:#1a56db;">{{ $pengajuan->logbook->count() }}</div>
                    <div style="font-size:12px;color:#64748b;">Total Logbook</div>
                </div>
                <div class="col-4 text-center p-3 rounded-3" style="background:#e3f8ef;">
                    <div style="font-size:22px;font-weight:800;color:#0ea472;">{{ $pengajuan->logbook->where('status','disetujui')->count() }}</div>
                    <div style="font-size:12px;color:#64748b;">Logbook Disetujui</div>
                </div>
                <div class="col-4 text-center p-3 rounded-3" style="background:#fff8e6;">
                    <div style="font-size:22px;font-weight:800;color:#f59e0b;">{{ $pengajuan->logbook->where('status','submitted')->count() }}</div>
                    <div style="font-size:12px;color:#64748b;">Menunggu Review</div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Kolom Kanan: Actions --}}
<div class="col-xl-4">

    {{-- Timeline Status --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title">Timeline Status</span></div>
        <div class="card-body">
            @php
            $timeline = [
                'diajukan'              => 'Diajukan Mahasiswa',
                'review_koordinator'    => 'Review Koordinator',
                'disetujui_koordinator' => 'Disetujui Koordinator',
                'review_mitra'          => 'Review Mitra',
                'diterima_mitra'        => 'Diterima Mitra',
                'berjalan'              => 'Sedang Berjalan',
                'selesai'               => 'Selesai',
            ];
            $statusKeys = array_keys($timeline);
            $currentIdx = array_search($pengajuan->status, $statusKeys);
            @endphp
            @foreach($timeline as $key => $label)
            @php $idx = array_search($key, $statusKeys); $done = $currentIdx >= $idx; @endphp
            <div class="d-flex align-items-start gap-3 mb-3">
                <div style="width:24px;height:24px;border-radius:50%;background:{{ $done ? '#1a56db' : '#e2e8f0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                    <i class="fas fa-{{ $done ? 'check' : 'circle' }}" style="font-size:10px;color:{{ $done ? '#fff' : '#cbd5e1' }};"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:{{ $key===$pengajuan->status ? '700' : '500' }};color:{{ $done ? '#0f172a' : '#94a3b8' }}">{{ $label }}</div>
                    @if($key === 'disetujui_koordinator' && $pengajuan->disetujui_koordinator_at)
                        <div style="font-size:11px;color:#64748b;">{{ $pengajuan->disetujui_koordinator_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ACTION CARDS --}}

    {{-- Setujui (jika pending review koordinator) --}}
    @if(in_array($pengajuan->status, ['diajukan','review_koordinator']))
    <div class="card mb-3 border-success">
        <div class="card-header" style="background:#e3f8ef;"><span class="card-header-title text-success"><i class="fas fa-check-circle me-2"></i>Setujui Pengajuan</span></div>
        <div class="card-body">
            <form action="{{ route('admin.pengajuan.setujui',$pengajuan) }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Assign Dosen Pembimbing <span class="text-danger">*</span></label>
                    <select name="dosen_id" class="form-select select2" required>
                        <option value="">— Pilih Dosen —</option>
                        @foreach($dosen_tersedia as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_lengkap }} (Sisa: {{ $d->sisa_kuota }} slot)</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan_koordinator" class="form-control" rows="2" placeholder="Catatan untuk mahasiswa..."></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-2"></i>Setujui & Teruskan ke Mitra</button>
            </form>
        </div>
    </div>

    <div class="card mb-3 border-danger">
        <div class="card-header" style="background:#fef2f2;"><span class="card-header-title text-danger"><i class="fas fa-times-circle me-2"></i>Tolak Pengajuan</span></div>
        <div class="card-body">
            <form action="{{ route('admin.pengajuan.tolak',$pengajuan) }}" method="POST">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="catatan_koordinator" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin menolak pengajuan ini?')">
                    <i class="fas fa-times me-2"></i>Tolak Pengajuan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Aktifkan (jika diterima mitra) --}}
    @if($pengajuan->status === 'diterima_mitra')
    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <i class="fas fa-play-circle fa-3x text-success mb-3"></i>
            <p style="font-size:14px;color:#64748b;">Mitra telah menerima mahasiswa. Aktifkan status magang.</p>
            <form action="{{ route('admin.pengajuan.mulai',$pengajuan) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-success w-100"><i class="fas fa-play me-2"></i>Aktifkan Magang</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Selesaikan --}}
    @if($pengajuan->status === 'berjalan')
    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <form action="{{ route('admin.pengajuan.selesai',$pengajuan) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-outline-secondary w-100" onclick="return confirm('Tandai magang selesai?')">
                    <i class="fas fa-flag-checkered me-2"></i>Tandai Selesai
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Generate Sertifikat --}}
    @if($pengajuan->status === 'selesai' && $pengajuan->penilaian?->lulus && !$pengajuan->sertifikat)
    <div class="card mb-3 border-warning">
        <div class="card-body text-center py-4">
            <i class="fas fa-certificate fa-2x text-warning mb-2"></i>
            <p style="font-size:13px;color:#64748b;">Mahasiswa lulus penilaian. Terbitkan sertifikat.</p>
            <form action="{{ route('admin.sertifikat.generate',$pengajuan) }}" method="POST">
                @csrf
                <button class="btn btn-warning w-100"><i class="fas fa-award me-2"></i>Generate Sertifikat</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Surat Otomatis --}}
    @if(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan', 'selesai']))
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Surat Otomatis</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.surat.pengantar.preview', $pengajuan) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview
                        </a>
                        <a href="{{ route('admin.surat.pengantar', $pengajuan) }}" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.surat.pengajuan.preview', $pengajuan) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview
                        </a>
                        <a href="{{ route('admin.surat.pengajuan', $pengajuan) }}" class="btn btn-info text-white">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($pengajuan->sertifikat)
    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <i class="fas fa-certificate fa-2x text-success mb-2"></i>
            <div style="font-size:13px;font-weight:600;">{{ $pengajuan->sertifikat->nomor_sertifikat }}</div>
            <a href="{{ route('admin.sertifikat.download',$pengajuan->sertifikat) }}" class="btn btn-outline-success mt-2">
                <i class="fas fa-download me-2"></i>Download Sertifikat
            </a>
        </div>
    </div>
    @endif

    {{-- Diskusi --}}
    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <i class="fas fa-comments fa-2x text-primary mb-2"></i>
            <p style="font-size:13px;color:#64748b;">Forum diskusi untuk pengajuan ini.</p>
            <a href="{{ route('diskusi.index', $pengajuan) }}" class="btn btn-primary w-100">
                <i class="fas fa-comments me-2"></i>Buka Diskusi
            </a>
        </div>
    </div>

</div>
</div>
@endsection
