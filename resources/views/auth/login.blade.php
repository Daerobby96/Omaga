<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — SIMAGA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f8fafc;min-height:100vh;display:flex;align-items:center;justify-content:center;}
.login-wrap{width:100%;max-width:420px;padding:16px;}
.login-card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(15,23,42,.1);overflow:hidden;}
.login-header{background:linear-gradient(135deg,#1a56db,#0ea472);padding:36px 36px 28px;text-align:center;}
.login-icon{width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;}
.login-icon i{color:#fff;font-size:24px;}
.login-title{font-size:22px;font-weight:800;color:#fff;margin:0;}
.login-sub{font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;}
.login-body{padding:32px 36px 36px;}
.form-label{font-size:13px;font-weight:600;color:#0f172a;}
.form-control{border-radius:10px;border:1px solid #e2e8f0;font-size:14px;padding:10px 14px;}
.form-control:focus{border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.12);}
.input-group .form-control{border-right:none;}
.input-group .input-group-text{background:#fff;border:1px solid #e2e8f0;border-left:none;border-radius:0 10px 10px 0;cursor:pointer;color:#64748b;}
.btn-login{background:#1a56db;border:none;border-radius:10px;padding:11px;font-size:15px;font-weight:700;width:100%;color:#fff;transition:background .2s;}
.btn-login:hover{background:#1240aa;}
.divider{text-align:center;position:relative;margin:18px 0;font-size:12px;color:#94a3b8;}
.divider::before,.divider::after{content:'';position:absolute;top:50%;width:42%;height:1px;background:#e2e8f0;}
.divider::before{left:0;}.divider::after{right:0;}
.alert{border-radius:10px;font-size:13px;}
</style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="login-title">SIMAGA</div>
            <div class="login-sub">Sistem Informasi Manajemen Magang</div>
        </div>
        <div class="login-body">
            @if(session('success'))
                <div class="alert alert-success mb-3"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if(isset($expired) && $expired)
                <div class="alert alert-warning mb-3"><i class="fas fa-clock me-2"></i>Sesi Anda telah habis. Silakan login kembali.</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-3"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@institusi.ac.id" autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label d-flex justify-content-between">
                        Password
                        <a href="#" style="font-size:12px;color:#1a56db;font-weight:500;">Lupa password?</a>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password">
                        <span class="input-group-text" onclick="togglePwd()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:13px;">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>

            <div class="divider">atau</div>

            <a href="{{ route('mitra.register') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px;font-size:14px;font-weight:600;">
                <i class="fas fa-building me-2"></i>Daftar sebagai Mitra Perusahaan
            </a>

            <div class="divider">atau</div>

            <a href="{{ route('mahasiswa.register') }}" class="btn btn-outline-primary w-100" style="border-radius:10px;font-size:14px;font-weight:600;">
                <i class="fas fa-user-graduate me-2"></i>Daftar sebagai Mahasiswa
            </a>

            <div class="text-center mt-4" style="font-size:12px;color:#94a3b8;">
                Akun mahasiswa & dosen didaftarkan oleh Koordinator Magang
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd(){
    const i=document.getElementById('passwordInput');
    const e=document.getElementById('eyeIcon');
    if(i.type==='password'){i.type='text';e.classList.replace('fa-eye','fa-eye-slash');}
    else{i.type='password';e.classList.replace('fa-eye-slash','fa-eye');}
}
</script>
</body>
</html>
