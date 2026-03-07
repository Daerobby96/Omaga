{{-- resources/views/admin/mahasiswa/create.blade.php --}}
@extends('layouts.app')
@section('title', isset($mahasiswa) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')
@section('page-title', isset($mahasiswa) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')
@section('page-sub', isset($mahasiswa) ? 'Perbarui data mahasiswa' : 'Daftarkan mahasiswa baru ke sistem')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">

<form action="{{ isset($mahasiswa) ? route('admin.mahasiswa.update',$mahasiswa) : route('admin.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($mahasiswa)) @method('PUT') @endif

    {{-- Data Akun --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-user-circle me-2 text-primary"></i>Data Akun Login</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $mahasiswa->user->email ?? '') }}" placeholder="email@student.ac.id">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password {{ isset($mahasiswa) ? '(Kosongkan jika tidak diubah)' : '*' }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="{{ isset($mahasiswa) ? 'Isi untuk mengubah password' : 'Min. 8 karakter' }}">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Data Pribadi --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-id-card me-2 text-success"></i>Data Pribadi Mahasiswa</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                           value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap ?? '') }}" placeholder="Nama sesuai KTP">
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                           value="{{ old('nim', $mahasiswa->nim ?? '') }}" placeholder="Nomor Induk Mahasiswa"
                           {{ isset($mahasiswa) ? 'readonly' : '' }}>
                    @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5">
                    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                    <input type="text" name="program_studi" class="form-control @error('program_studi') is-invalid @enderror"
                           value="{{ old('program_studi', $mahasiswa->program_studi ?? '') }}" placeholder="Teknik Informatika">
                    @error('program_studi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fakultas <span class="text-danger">*</span></label>
                    <input type="text" name="fakultas" class="form-control @error('fakultas') is-invalid @enderror"
                           value="{{ old('fakultas', $mahasiswa->fakultas ?? '') }}" placeholder="Teknik">
                    @error('fakultas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select @error('semester') is-invalid @enderror">
                        @for($i=1;$i<=14;$i++)
                            <option value="{{ $i }}" @selected(old('semester', $mahasiswa->semester ?? '') == $i)>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Angkatan <span class="text-danger">*</span></label>
                    <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror"
                           value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}" placeholder="{{ date('Y')-3 }}" maxlength="4">
                    @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">IPK</label>
                    <input type="number" step="0.01" min="0" max="4" name="ipk" class="form-control @error('ipk') is-invalid @enderror"
                           value="{{ old('ipk', $mahasiswa->ipk ?? '') }}" placeholder="3.75">
                    @error('ipk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Akademik</label>
                    <select name="status_akademik" class="form-select">
                        <option value="aktif" @selected(old('status_akademik',$mahasiswa->status_akademik??'aktif')==='aktif')>Aktif</option>
                        <option value="cuti"  @selected(old('status_akademik',$mahasiswa->status_akademik??'')==='cuti')>Cuti</option>
                        <option value="lulus" @selected(old('status_akademik',$mahasiswa->status_akademik??'')==='lulus')>Lulus</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Dokumen --}}
    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-file-upload me-2 text-warning"></i>Upload Dokumen</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <div class="form-text">JPG/PNG, maks. 2MB</div>
                    @if(isset($mahasiswa) && $mahasiswa->foto)
                        <img src="{{ Storage::url($mahasiswa->foto) }}" class="mt-2 rounded" style="height:60px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">CV / Resume</label>
                    <input type="file" name="cv" class="form-control" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                    @if(isset($mahasiswa) && $mahasiswa->cv)
                        <a href="{{ Storage::url($mahasiswa->cv) }}" target="_blank" class="text-primary d-block mt-1" style="font-size:12px;"><i class="fas fa-file-pdf me-1"></i>File saat ini</a>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Transkrip Nilai</label>
                    <input type="file" name="transkrip" class="form-control" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                    @if(isset($mahasiswa) && $mahasiswa->transkrip)
                        <a href="{{ Storage::url($mahasiswa->transkrip) }}" target="_blank" class="text-primary d-block mt-1" style="font-size:12px;"><i class="fas fa-file-pdf me-1"></i>File saat ini</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-2"></i>{{ isset($mahasiswa) ? 'Perbarui Data' : 'Simpan Mahasiswa' }}
        </button>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection
