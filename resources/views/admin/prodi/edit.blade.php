{{-- resources/views/admin/prodi/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Program Studi')
@section('page-title','Edit Program Studi')
@section('page-sub', $prodi->nama_prodi)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">

<form action="{{ route('admin.prodi.update', $prodi->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-university me-2 text-primary"></i>Data Program Studi</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                    <input type="text" name="nama_prodi" class="form-control @error('nama_prodi') is-invalid @enderror" value="{{ old('nama_prodi', $prodi->nama_prodi) }}">
                    @error('nama_prodi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kode Prodi <span class="text-danger">*</span></label>
                    <input type="text" name="kode_prodi" class="form-control @error('kode_prodi') is-invalid @enderror" value="{{ old('kode_prodi', $prodi->kode_prodi) }}">
                    @error('kode_prodi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Fakultas</label>
                    <input type="text" name="fakultas" class="form-control" value="{{ old('fakultas', $prodi->fakultas) }}" placeholder="Contoh: Teknik">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenjang <span class="text-danger">*</span></label>
                    <select name="jenjang" class="form-select @error('jenjang') is-invalid @enderror">
                        <option value="S1" {{ old('jenjang', $prodi->jenjang) == 'S1' ? 'selected' : '' }}>S1 - Strata 1</option>
                        <option value="D3" {{ old('jenjang', $prodi->jenjang) == 'D3' ? 'selected' : '' }}>D3 - Diploma 3</option>
                        <option value="D4" {{ old('jenjang', $prodi->jenjang) == 'D4' ? 'selected' : '' }}>D4 - Diploma 4</option>
                    </select>
                    @error('jenjang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Akreditasi</label>
                    <select name="akreditasi" class="form-select">
                        <option value="">Pilih Akreditasi</option>
                        <option value="A" {{ old('akreditasi', $prodi->akreditasi) == 'A' ? 'selected' : '' }}>A - Unggul</option>
                        <option value="B" {{ old('akreditasi', $prodi->akreditasi) == 'B' ? 'selected' : '' }}>B - Baik Sekali</option>
                        <option value="C" {{ old('akreditasi', $prodi->akreditasi) == 'C' ? 'selected' : '' }}>C - Baik</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tahun Akreditasi</label>
                    <input type="number" name="tahun_akreditasi" class="form-control" value="{{ old('tahun_akreditasi', $prodi->tahun_akreditasi) }}" min="2000" max="{{ date('Y') }}">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-12">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="aktif" {{ old('status', $prodi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $prodi->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
        <a href="{{ route('admin.prodi.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection
