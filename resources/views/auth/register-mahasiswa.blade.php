<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Mahasiswa — SIMAGA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f8fafc;min-height:100vh;padding:40px 16px;}
.register-wrap{width:100%;max-width:560px;margin:0 auto;}
.register-card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(15,23,42,.1);overflow:hidden;}
.register-header{background:linear-gradient(135deg,#1a56db,#0ea472);padding:32px 36px 24px;text-align:center;}
.register-icon{width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.register-icon i{color:#fff;font-size:22px;}
.register-title{font-size:20px;font-weight:800;color:#fff;margin:0;}
.register-sub{font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;}
.register-body{padding:32px 36px 36px;}
.form-label{font-size:13px;font-weight:600;color:#0f172a;}
.form-control,.form-select{border-radius:10px;border:1px solid #e2e8f0;font-size:14px;padding:10px 14px;}
.form-control:focus,.form-select:focus{border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.12);}
.btn-register{background:#1a56db;border:none;border-radius:10px;padding:11px;font-size:15px;font-weight:700;width:100%;color:#fff;transition:background .2s;}
.btn-register:hover{background:#1240aa;}
.alert{border-radius:10px;font-size:13px;}
h6{font-size:12px;text-transform:uppercase;letter-spacing:0.5px;}
</style>
</head>
<body>
<div class="register-wrap">
    <div class="register-card">
        <div class="register-header">
            <div class="register-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="register-title">Daftar Mahasiswa</div>
            <div class="register-sub">Registrasi Akun Mahasiswa Magang</div>
        </div>
        <div class="register-body">
            @if(session('success'))
                <div class="alert alert-success mb-3"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('mahasiswa.register') }}" method="POST">
                @csrf
                
                <h6 class="mb-3 fw-bold text-muted">Data Akademik</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" value="{{ old('nim') }}" placeholder="2021001001" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" placeholder="Nama Lengkap Mahasiswa" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi') }}" placeholder="Teknik Informatika" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fakultas</label>
                        <input type="text" name="fakultas" class="form-control" value="{{ old('fakultas') }}" placeholder="Teknik" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" value="{{ old('semester') }}" min="1" max="14" placeholder="7" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Angkatan</label>
                        <input type="number" name="angkatan" class="form-control" value="{{ old('angkatan') }}" min="2020" max="2030" placeholder="2021" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">IPK (Opsional)</label>
                        <input type="number" name="ipk" class="form-control" value="{{ old('ipk') }}" min="0" max="4" step="0.01" placeholder="3.50">
                    </div>
                </div>

                <h6 class="mb-3 fw-bold text-muted">Data Pribadi</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="mahasiswa@student.ac.id" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="081234567890">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat tinggal">{{ old('alamat') }}</textarea>
                </div>

                <h6 class="mb-3 fw-bold text-muted">Password</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" style="font-size:13px;color:#1a56db;font-weight:500;">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
