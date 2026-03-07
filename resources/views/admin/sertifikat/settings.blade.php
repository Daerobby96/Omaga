{{-- resources/views/admin/sertifikat/settings.blade.php --}}
@extends('layouts.app')
@section('title','Pengaturan Sertifikat')
@section('page-title','Pengaturan Sertifikat')
@section('page-sub','Kustomisasi tampilan dan konten sertifikat magang')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.sertifikat.settings') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Preview Background --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title">Tampilan Sertifikat</span>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Background image tidak didukung oleh DomPDF. Silakan edit PDF secara manual setelah download jika ingin menambahkan background.
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Warna Utama</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="primary_color" class="form-control form-control-color" value="{{ $settings['primary_color'] }}">
                        <input type="text" class="form-control" value="{{ $settings['primary_color'] }}" style="width:80px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Warna Aksen (Border)</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="accent_color" class="form-control form-control-color" value="{{ $settings['accent_color'] }}">
                        <input type="text" class="form-control" value="{{ $settings['accent_color'] }}" style="width:80px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Logo Universitas --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-university me-2"></i>Logo Universitas</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Upload Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                    <div class="form-text">Format: PNG, JPG, atau SVG. Background transparan disarankan.</div>
                </div>
                @if(!empty($settings['logo']))
                <div class="col-md-12">
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo saat ini" style="max-height:80px; border:1px solid #ddd; padding:5px;">
                        <span class="ms-2 text-muted">Logo saat ini</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tanda Tangan --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-signature me-2"></i>Tanda Tangan</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanda Tangan Koordinator</label>
                    <input type="file" name="ttd_koordinator" class="form-control" accept="image/png,image/jpeg">
                    <div class="form-text">Format: PNG dengan background transparan</div>
                    @if(!empty($settings['ttd_koordinator']))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $settings['ttd_koordinator']) }}" alt="TTD Koordinator" style="max-height:60px; border:1px solid #ddd; padding:5px;">
                        <span class="ms-2 text-muted">Tersimpan</span>
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Koordinator</label>
                    <input type="text" name="nama_koordinator" class="form-control" value="{{ $settings['nama_koordinator'] ?? '' }}" placeholder="Dr. Budi Santoso, M.T.">
                </div>
            </div>
        </div>
    </div>

    {{-- Data Universitas --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-university me-2"></i>Data Universitas</span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Universitas</label>
                    <input type="text" name="university_name" class="form-control" value="{{ $settings['university_name'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fakultas</label>
                    <input type="text" name="faculty" class="form-control" value="{{ $settings['faculty'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="study_program" class="form-control" value="{{ $settings['study_program'] }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="address" class="form-control" value="{{ $settings['address'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Preview --}}
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-eye me-2"></i>Preview Cepat</span>
        </div>
        <div class="card-body text-center">
            <p class="text-muted mb-3">Simpan pengaturan terlebih dahulu untuk melihat preview lengkap</p>
            <div style="width:100%; max-width:400px; height:280px; margin:0 auto; border:2px solid {{ $settings['accent_color'] }}; display:flex; align-items:center; justify-content:center; background:#fff;">
                <div style="text-align:center;">
                    <div style="font-size:14px; font-weight:bold; color:{{ $settings['primary_color'] }};">
                        {{ $settings['university_name'] }}
                    </div>
                    <div style="font-size:10px; color:#64748b;">
                        {{ $settings['faculty'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Simpan Pengaturan
        </button>
        <a href="{{ route('admin.sertifikat.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">
            Kembali
        </a>
    </div>
</form>
</div>
</div>

@push('scripts')
<script>
// Background type selection removed - using simple border style only
</script>
@endpush
@endsection
