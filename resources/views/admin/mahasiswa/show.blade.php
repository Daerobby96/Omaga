{{-- resources/views/admin/mahasiswa/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Mahasiswa')
@section('page-title','Detail Mahasiswa')
@section('page-sub', $mahasiswa->nama_lengkap)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

{{-- Data Mahasiswa --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="card-header-title"><i class="fas fa-user-graduate me-2 text-primary"></i>Data Mahasiswa</span>
        <span class="badge bg-{{ $mahasiswa->status_akademik === 'aktif' ? 'success' : ($mahasiswa->status_akademik === 'lulus' ? 'info' : 'secondary') }}">
            {{ ucfirst($mahasiswa->status_akademik) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">NIM</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->nama_lengkap }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">Program Studi</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->program_studi }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fakultas</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->fakultas ?? '-' }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <label class="form-label">Semester</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->semester }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Angkatan</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->angkatan }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">IPK</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->ipk ?? '-' }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->no_hp ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->user->email }}" readonly>
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" value="{{ $mahasiswa->alamat ?? '-' }}" readonly>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Pengajuan Magang --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="card-header-title">
            <i class="fas fa-briefcase me-2 text-success"></i>Riwayat Magang ({{ $mahasiswa->pengajuan->count() }})
        </span>
    </div>
    <div class="card-body">
        @forelse($mahasiswa->pengajuan as $p)
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="fw-semibold">{{ $p->mitra->nama_perusahaan }}</div>
                    <small class="text-muted">{{ $p->mitra->alamat }}</small>
                </div>
                <span class="badge bg-{{ $p->status === 'berjalan' ? 'primary' : ($p->status === 'selesai' ? 'success' : 'secondary') }}">
                    {{ ucfirst($p->status) }}
                </span>
            </div>
            <div class="row g-2 small">
                <div class="col-md-4">
                    <strong>Periode:</strong> {{ $p->tanggal_mulai->locale('id')->translatedFormat('d M Y') }} - {{ $p->tanggal_selesai->locale('id')->translatedFormat('d M Y') }}
                </div>
                <div class="col-md-4">
                    <strong>Dosen:</strong> {{ $p->dosen->nama_lengkap ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Posisi:</strong> {{ $p->posisi_magang }}
                </div>
            </div>
            @if($p->penilaian)
            <div class="mt-2 p-2 bg-light rounded">
                <strong>Nilai Akhir:</strong> {{ $p->penilaian->nilai_akhir ?? '-' }} | 
                <strong>Grade:</strong> {{ $p->penilaian->grade ?? '-' }} |
                <strong>Status:</strong> {{ $p->penilaian->lulus ? 'Lulus' : 'Tidak Lulus' }}
            </div>
            @endif
        </div>
        @empty
        <p class="text-muted mb-0">Belum ada riwayat magang</p>
        @endforelse
    </div>
</div>

{{-- Riwayat Penilaian --}}
@if($mahasiswa->penilaian->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <span class="card-header-title">
            <i class="fas fa-star me-2 text-warning"></i>Riwayat Penilaian ({{ $mahasiswa->penilaian->count() }})
        </span>
    </div>
    <div class="card-body">
        @foreach($mahasiswa->penilaian as $penilaian)
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">{{ $penilaian->pengajuan->mitra->nama_perusahaan }}</div>
                <span class="badge bg-{{ $penilaian->lulus ? 'success' : 'danger' }}">
                    {{ $penilaian->lulus ? 'Lulus' : 'Tidak Lulus' }}
                </span>
            </div>
            <div class="row g-2 small">
                <div class="col-md-3">
                    <strong>Nilai Akhir:</strong> {{ $penilaian->nilai_akhir ?? '-' }}
                </div>
                <div class="col-md-3">
                    <strong>Grade:</strong> {{ $penilaian->grade ?? '-' }}
                </div>
                <div class="col-md-3">
                    <strong>Nilai Dosen:</strong> {{ $penilaian->nilai_dosen ?? '-' }}
                </div>
                <div class="col-md-3">
                    <strong>Nilai Mitra:</strong> {{ $penilaian->nilai_mitra ?? '-' }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Sertifikat --}}
@if($mahasiswa->sertifikat->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <span class="card-header-title">
            <i class="fas fa-certificate me-2 text-info"></i>Sertifikat ({{ $mahasiswa->sertifikat->count() }})
        </span>
    </div>
    <div class="card-body">
        @foreach($mahasiswa->sertifikat as $sertifikat)
        <div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2">
            <div>
                <div class="fw-semibold">{{ $sertifikat->pengajuan->mitra->nama_perusahaan }}</div>
                <small class="text-muted">No: {{ $sertifikat->nomor_sertifikat }}</small>
            </div>
            <a href="{{ asset('storage/'.$sertifikat->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="fas fa-download me-1"></i>Download
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Aksi --}}
<div class="d-flex gap-3">
      <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus? Data mahasiswa dan akunnya akan dihapus.')">
            <i class="fas fa-trash me-2"></i>Hapus
        </button>
    </form>
    <a href="{{ route('admin.mahasiswa.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

</div>
</div>
@endsection
