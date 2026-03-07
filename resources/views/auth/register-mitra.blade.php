<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Mitra — SIMAGA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f8fafc;min-height:100vh;padding:40px 16px;}
.register-wrap{width:100%;max-width:520px;margin:0 auto;}
.register-card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(15,23,42,.1);overflow:hidden;}
.register-header{background:linear-gradient(135deg,#0d9488,#0ea472);padding:32px 36px 24px;text-align:center;}
.register-icon{width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.register-icon i{color:#fff;font-size:22px;}
.register-title{font-size:20px;font-weight:800;color:#fff;margin:0;}
.register-sub{font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;}
.register-body{padding:32px 36px 36px;}
.form-label{font-size:13px;font-weight:600;color:#0f172a;}
.form-control{border-radius:10px;border:1px solid #e2e8f0;font-size:14px;padding:10px 14px;}
.form-control:focus{border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.12);}
.btn-register{background:#0d9488;border:none;border-radius:10px;padding:11px;font-size:15px;font-weight:700;width:100%;color:#fff;transition:background .2s;}
.btn-register:hover{background:#0f766e;}
.alert{border-radius:10px;font-size:13px;}
</style>
</head>
<body>
<div class="register-wrap">
    <div class="register-card">
        <div class="register-header">
            <div class="register-icon"><i class="fas fa-building"></i></div>
            <div class="register-title">Daftar Mitra</div>
            <div class="register-sub">Registrasi Perusahaan Mitra Magang</div>
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

            <form action="{{ route('mitra.register') }}" method="POST">
                @csrf
                <h6 class="mb-3 fw-bold text-muted">Data Perusahaan</h6>
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="{{ old('nama_perusahaan') }}" placeholder="PT. Nama Perusahaan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bidang Usaha</label>
                    <input type="text" name="bidang_usaha" class="form-control" value="{{ old('bidang_usaha') }}" placeholder="Teknologi Informasi" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Perusahaan</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Alamat Perusahaan" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Perusahaan</label>
                        <input type="email" name="email_perusahaan" class="form-control" value="{{ old('email_perusahaan') }}" placeholder="hr@perusahaan.com" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Perusahaan</label>
                    <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat perusahaan">{{ old('deskripsi') }}</textarea>
                </div>

                <h6 class="mb-3 fw-bold text-muted">Data Kontak</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Kontak Person</label>
                        <input type="text" name="nama_kontak" class="form-control" value="{{ old('nama_kontak') }}" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan_kontak" class="form-control" value="{{ old('jabatan_kontak') }}" placeholder="HR Manager">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Akun Login</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@domain.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="081234567890">
                    </div>
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
                <a href="{{ route('login') }}" style="font-size:13px;color:#0d9488;font-weight:500;">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
