{{-- resources/views/admin/dosen/create.blade.php --}}
@extends('layouts.app')
@section('title','Tambah Dosen')
@section('page-title','Tambah Dosen Baru')
@section('page-sub','Formulir tambah data dosen')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<form action="{{ route('admin.dosen.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-user me-2 text-primary"></i>Data User</span></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}">
                @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-graduation-cap me-2 text-success"></i>Data Dosen</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIDN <span class="text-danger">*</span></label>
                    <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn') }}">
                    @error('nidn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                    <select name="program_studi" class="form-select @error('program_studi') is-invalid @enderror">
                        <option value="">Pilih Program Studi</option>
                        <option value="Teknik Informatika" {{ old('program_studi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="Sistem Informasi" {{ old('program_studi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Ilmu Komputer" {{ old('program_studi') == 'Ilmu Komputer' ? 'selected' : '' }}>Ilmu Komputer</option>
                        <option value="Manajemen Informatika" {{ old('program_studi') == 'Manajemen Informatika' ? 'selected' : '' }}>Manajemen Informatika</option>
                        <option value="Teknik Komputer" {{ old('program_studi') == 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                    </select>
                    @error('program_studi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fakultas <span class="text-danger">*</span></label>
                    <select name="fakultas" class="form-select @error('fakultas') is-invalid @enderror">
                        <option value="">Pilih Fakultas</option>
                        <option value="Teknik" {{ old('fakultas') == 'Teknik' ? 'selected' : '' }}>Teknik</option>
                        <option value="Bisnis" {{ old('fakultas') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="Ilmu Sosial" {{ old('fakultas') == 'Ilmu Sosial' ? 'selected' : '' }}>Ilmu Sosial</option>
                    </select>
                    @error('fakultas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Kuota Bimbingan</label>
                    <input type="number" name="kuota_bimbingan" class="form-control" value="{{ old('kuota_bimbingan', 5) }}" min="0" max="20">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan Fungsional</label>
                    <select name="jabatan_fungsional" class="form-select">
                        <option value="">Pilih Jabatan</option>
                        <option value="Tenaga Pengajar" {{ old('jabatan_fungsional') == 'Tenaga Pengajar' ? 'selected' : '' }}>Tenaga Pengajar</option>
                        <option value="Asisten Ahli" {{ old('jabatan_fungsional') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                        <option value="Lektor" {{ old('jabatan_fungsional') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                        <option value="Lektor Kepala" {{ old('jabatan_fungsional') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                        <option value="Guru Besar" {{ old('jabatan_fungsional') == 'Guru Besar' ? 'selected' : '' }}>Guru Besar</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="is_ketua_prodi" class="form-check-input" id="is_ketua_prodi" value="1" {{ old('is_ketua_prodi') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_ketua_prodi">
                            Apakah Ketua Program Studi?
                        </label>
                    </div>
                </div>
                <div class="col-md-6" id="prodi_field" style="display: none;">
                    <label class="form-label">Program Studi yang Dikelola</label>
                    <select name="prodi_yang_dikelola" class="form-select">
                        <option value="">Pilih Program Studi</option>
                        <option value="Teknik Informatika" {{ old('prodi_yang_dikelola') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="Sistem Informasi" {{ old('prodi_yang_dikelola') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Ilmu Komputer" {{ old('prodi_yang_dikelola') == 'Ilmu Komputer' ? 'selected' : '' }}>Ilmu Komputer</option>
                        <option value="Manajemen Informatika" {{ old('prodi_yang_dikelola') == 'Manajemen Informatika' ? 'selected' : '' }}>Manajemen Informatika</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>Simpan
        </button>
        <a href="{{ route('admin.dosen.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('is_ketua_prodi').addEventListener('change', function() {
    document.getElementById('prodi_field').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
