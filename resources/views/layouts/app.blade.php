<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — SIMAGA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<style>
:root{--sidebar-w:262px;--topbar-h:64px;--primary:#1a56db;--primary-dark:#1240aa;--primary-light:#ebf0ff;--accent:#0ea472;--accent-light:#e3f8ef;--warn:#f59e0b;--danger:#ef4444;--dark:#0f172a;--muted:#64748b;--border:#e2e8f0;--bg:#f8fafc;--sidebar-bg:#0f172a;--radius:14px;}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--dark);overflow-x:hidden}a{text-decoration:none}
/* Sidebar */
.sidebar{width:var(--sidebar-w);height:100vh;position:fixed;left:0;top:0;background:var(--sidebar-bg);display:flex;flex-direction:column;z-index:1000;overflow-y:auto;overflow-x:hidden;pointer-events:auto}
.sidebar::-webkit-scrollbar{width:3px;}.sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:2px;}
.sb-brand{padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px;flex-shrink:0;}
.sb-icon{width:40px;height:40px;border-radius:11px;background:var(--primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-icon i{color:#fff;font-size:18px;}.sb-name{font-size:15px;font-weight:800;color:#fff;line-height:1.1;}.sb-sub{font-size:10px;color:rgba(255,255,255,.35);}
.nav-grp{padding:4px 12px;}.nav-lbl{font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.25);padding:10px 8px 4px;}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;color:rgba(255,255,255,.55);font-size:13.5px;font-weight:500;margin-bottom:2px;transition:all .15s;}
.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;}.nav-item.active{background:var(--primary);color:#fff;}
.nav-item i{width:18px;text-align:center;font-size:14px;flex-shrink:0;}
.nav-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;min-width:20px;text-align:center;}
.nav-badge.warn{background:var(--warn);}.nav-badge.red{background:var(--danger);}
.sb-footer{margin-top:auto;padding:12px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0;}
.user-pill{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;cursor:pointer;transition:background .15s;}
.user-pill:hover{background:rgba(255,255,255,.07);}
.u-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;overflow:hidden;}
.u-avatar img{width:34px;height:34px;object-fit:cover;}.u-name{font-size:12.5px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;}.u-role{font-size:10px;color:rgba(255,255,255,.35);}
/* Main */
.main-wrap{margin-left:var(--sidebar-w);min-height:100vh;position:relative}
.topbar{height:var(--topbar-h);background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;gap:12px;position:sticky;top:0;z-index:100;width:100%}
.topbar-title{font-size:17px;font-weight:700;color:var(--dark);}.topbar-sub{font-size:12px;color:var(--muted);}
.tb-right{margin-left:auto;display:flex;align-items:center;gap:10px;}
.ic-btn{width:38px;height:38px;border-radius:10px;border:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;position:relative;flex-shrink:0}
.ic-btn:hover{background:var(--bg);color:var(--dark)}
.notif-dot{width:8px;height:8px;background:var(--danger);border-radius:50%;position:absolute;top:7px;right:7px;border:2px solid #fff}
/* Menu toggle button for mobile */
.menu-toggle{background:var(--primary);color:#fff !important;border:1px solid var(--primary)}
.menu-toggle:hover{background:var(--primary-dark);color:#fff !important}
.page-content{padding:26px 28px;}
/* Alert */
.alert{border-radius:10px;font-size:14px;border:none}
.alert-success{background:#e3f8ef;color:#065f46}.alert-danger{background:#fef2f2;color:#7f1d1d}
.toast-notification{position:fixed;top:20px;right:20px;padding:16px 24px;border-radius:12px;color:#fff;font-size:14px;font-weight:500;display:flex;align-items:center;gap:10px;z-index:9999;box-shadow:0 10px 40px rgba(0,0,0,.2);transform:translateX(400px);transition:transform .3s ease}
.toast-notification.show{transform:translateX(0)}
.toast-success{background:linear-gradient(135deg,#10b981,#059669)}
.toast-error{background:linear-gradient(135deg,#ef4444,#dc2626)}
/* Cards */
.card{border:1px solid var(--border);border-radius:var(--radius);}
.card-header{background:#fff;border-bottom:1px solid var(--border);padding:16px 20px;border-radius:var(--radius) var(--radius) 0 0 !important;display:flex;align-items:center;justify-content:space-between;}
.card-header-title{font-size:15px;font-weight:700;color:var(--dark);}
.card-body{padding:20px;}
/* Stat Cards */
.stat-card{background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:20px;position:relative;overflow:hidden;}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.stat-card.blue::before{background:var(--primary);}.stat-card.green::before{background:var(--accent);}.stat-card.orange::before{background:var(--warn);}.stat-card.red::before{background:var(--danger);}.stat-card.purple::before{background:#8b5cf6;}
.stat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:14px;}
.stat-icon.blue{background:var(--primary-light);color:var(--primary);}.stat-icon.green{background:var(--accent-light);color:var(--accent);}.stat-icon.orange{background:#fff8e6;color:var(--warn);}.stat-icon.red{background:#fef2f2;color:var(--danger);}.stat-icon.purple{background:#f3f0ff;color:#8b5cf6;}
.stat-value{font-size:30px;font-weight:800;color:var(--dark);line-height:1;}.stat-label{font-size:13px;color:var(--muted);margin-top:4px;}
.stat-change{font-size:12px;margin-top:8px;}.stat-change.up{color:var(--accent);}.stat-change.down{color:var(--danger);}
/* Table */
.table-custom{width:100%;border-collapse:collapse;}
.table-custom th{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;background:var(--bg);border-bottom:1px solid var(--border);}
.table-custom td{padding:13px 14px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle;}
.table-custom tr:last-child td{border-bottom:none;}.table-custom tbody tr:hover td{background:#fcfcfc;}
/* Avatar */
.av{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;}
.av-sm{width:28px;height:28px;font-size:11px;}.av-lg{width:56px;height:56px;font-size:20px;}
/* Progress */
.prog-wrap{height:6px;background:var(--border);border-radius:4px;overflow:hidden;}.prog-fill{height:100%;border-radius:4px;}
/* Badges */
.bdg{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;gap:4px;}
/* Button */
.btn-primary{background:var(--primary);border-color:var(--primary);}.btn-primary:hover{background:var(--primary-dark);border-color:var(--primary-dark);}
.btn-outline-primary{color:var(--primary);border-color:var(--primary);}.btn-outline-primary:hover{background:var(--primary);color:#fff;}
.btn-secondary{background:var(--muted);border-color:var(--muted);}.btn-secondary:hover{background:#475569;border-color:#475569;}
.btn{font-weight:600;font-size:13px;padding:8px 16px;border-radius:8px;transition:all .2s;}
.btn i{margin-right:6px;}
.btn-success{background:var(--accent);border-color:var(--accent);}.btn-success:hover{background:#0d8a62;border-color:#0d8a62;}
.btn-danger{background:var(--danger);border-color:var(--danger);}.btn-danger:hover{background:#dc2626;border-color:#dc2626;}
.btn-warning{background:var(--warn);border-color:var(--warn);color:#fff;}.btn-warning:hover{background:#d97706;border-color:#d97706;}
.btn-info{background:#0ea472;border-color:#0ea472;color:#fff;}.btn-info:hover{background:#0d8a62;border-color:#0d8a62;}
.btn-light{background:#f1f5f9;border-color:#e2e8f0;color:var(--dark);}.btn-light:hover{background:#e2e8f0;}
.btn-sm{padding:5px 12px;font-size:12px;}
.btn-lg{padding:12px 24px;font-size:15px;}
/* Form */
.form-label{font-size:13px;font-weight:600;color:var(--dark);margin-bottom:5px;}
.form-control,.form-select{font-size:13.5px;border-radius:9px;border:1px solid var(--border);padding:9px 13px;}
.form-control:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,86,219,.12);}
.is-invalid{border-color:var(--danger)!important;}.invalid-feedback{font-size:12px;}
/* Sidebar role classes */
.role-admin .sb-icon{background:#8b5cf6;}.role-dosen .sb-icon{background:#0ea472;}.role-mahasiswa .sb-icon{background:#1a56db;}.role-mitra .sb-icon{background:#f59e0b;}
/* Responsive Mobile - Enhanced */
.menu-toggle{display:none}
.sidebar-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;pointer-events:none;opacity:0;transition:opacity 0.3s;display:none}
.sidebar-overlay.show{display:block;pointer-events:auto;opacity:1}
@media(max-width:991px){
  .menu-toggle{display:flex !important}
  .sidebar{position:fixed;top:0;left:0;bottom:0;width:280px;transform:translateX(-100%);transition:transform 0.3s ease;z-index:1001}
  .sidebar.show{transform:translateX(0)}
  .topbar{position:fixed;top:0;left:0;right:0;z-index:1002;width:100%}
  .main-wrap{margin-left:0 !important;padding-top:var(--topbar-h)}
  .page-content{padding:12px !important;padding-top:calc(var(--topbar-h) + 12px) !important}
  .topbar{padding:0 12px !important}
  .topbar-title{font-size:15px !important}
  .stat-card{padding:12px !important}
  .stat-value{font-size:22px !important}
  .stat-icon{width:36px;height:36px;font-size:16px}
  .card-body{padding:12px !important}
  .table-custom th,.table-custom td{padding:8px 6px;font-size:11px}
  .btn{padding:6px 12px;font-size:12px}
  .form-control,.form-select{padding:8px 10px;font-size:13px}
}
/* Extra small devices */
@media(max-width:576px){
  .stat-value{font-size:20px !important}
  .stat-label{font-size:11px}
  .page-content{padding:8px !important;padding-top:calc(var(--topbar-h) + 8px) !important}
  .card-header{padding:12px !important}
  .card-body{padding:10px !important}
}
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar role-{{ auth()->user()->getRoleNames()->first() }}">
    {{-- Brand --}}
    <div class="sb-brand">
        <div class="sb-icon"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <div class="sb-name">OMEGA</div>
            <div class="sb-sub">Otomotisasi Mekanisme Magang</div>
        </div>
    </div>

    {{-- Menu Admin --}}
    @role('admin')
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('admin/dashboard')" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('admin/mahasiswa*')" href="{{ route('admin.mahasiswa.index') }}">
            <i class="fas fa-user-graduate"></i> Data Mahasiswa
        </a>
        <a class="nav-item @activeClass('admin/dosen*')" href="{{ route('admin.dosen.index') }}">
            <i class="fas fa-chalkboard-teacher"></i> Data Dosen
        </a>
        <a class="nav-item @activeClass('admin/prodi*')" href="{{ route('admin.prodi.index') }}">
            <i class="fas fa-university"></i> Data Prodi
        </a>
        <a class="nav-item @activeClass('admin/mitra*')" href="{{ route('admin.mitra.index') }}">
            <i class="fas fa-building"></i> Perusahaan Mitra
            @php $mitraPending = \App\Models\Mitra::where('status','pending')->count(); @endphp
            @if($mitraPending) <span class="nav-badge warn">{{ $mitraPending }}</span> @endif
        </a>
    </div>
    <div class="nav-grp">
        <div class="nav-lbl">Pengajuan & Proses</div>
        <a class="nav-item @activeClass('admin/pengajuan*')" href="{{ route('admin.pengajuan.index') }}">
            <i class="fas fa-file-alt"></i> Pengajuan Magang
            @php $pending = \App\Models\PengajuanMagang::pending()->count(); @endphp
            @if($pending) <span class="nav-badge red">{{ $pending }}</span> @endif
        </a>
        <a class="nav-item @activeClass('diskusi*')" href="{{ route('diskusi.index', ['pengajuan' => 1]) }}">
            <i class="fas fa-comments"></i> Diskusi
        </a>
        <a class="nav-item @activeClass('admin/sertifikat*')" href="{{ route('admin.sertifikat.index') }}">
            <i class="fas fa-certificate"></i> Sertifikat
        </a>
        <a class="nav-item @activeClass('admin/laporan*')" href="{{ route('admin.laporan.index') }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
    </div>
    @endrole

    {{-- Menu Mahasiswa --}}
    @role('mahasiswa')
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('mahasiswa/dashboard')" href="{{ route('mahasiswa.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('mahasiswa/pengajuan*')" href="{{ route('mahasiswa.pengajuan.index') }}">
            <i class="fas fa-file-alt"></i> Pengajuan Magang
        </a>
        <a class="nav-item @activeClass('mahasiswa/logbook*')" href="{{ route('mahasiswa.logbook.index') }}">
            <i class="fas fa-book-open"></i> Logbook Harian
        </a>
        <a class="nav-item @activeClass('mahasiswa/penilaian*')" href="{{ route('mahasiswa.penilaian.index') }}">
            <i class="fas fa-star"></i> Nilai & Hasil
        </a>
    </div>
    @endrole

    {{-- Menu Dosen --}}
    @role('dosen')
    @php $isKetuaProdi = auth()->user()->dosen && auth()->user()->dosen->is_ketua_prodi; @endphp
    @if(!$isKetuaProdi)
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('dosen/dashboard')" href="{{ route('dosen.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('dosen/bimbingan*')" href="{{ route('dosen.bimbingan.index') }}">
            <i class="fas fa-users"></i> Mahasiswa Bimbingan
        </a>
        <a class="nav-item @activeClass('dosen/logbook*')" href="{{ route('dosen.logbook.index') }}">
            <i class="fas fa-book-open"></i> Review Logbook
            @php $pending = \App\Models\Logbook::whereHas('pengajuan',fn($q)=>$q->where('dosen_id',auth()->user()->dosen?->id))->where('status','submitted')->count(); @endphp
            @if($pending) <span class="nav-badge red">{{ $pending }}</span> @endif
        </a>
        <a class="nav-item @activeClass('dosen/penilaian*')" href="{{ route('dosen.penilaian.index') }}">
            <i class="fas fa-star"></i> Penilaian
        </a>
    </div>
    @endif
    @endrole

    {{-- Menu Ketua Prodi --}}
    @role('ketua_prodi')
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('ketua-prodi/dashboard')" href="{{ route('ketua_prodi.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('ketua-prodi/prodi*')" href="{{ route('ketua_prodi.prodi.index') }}">
            <i class="fas fa-university"></i> Data Prodi
        </a>
        <a class="nav-item @activeClass('ketua-prodi/mahasiswa*')" href="{{ route('ketua_prodi.mahasiswa.index') }}">
            <i class="fas fa-user-graduate"></i> Mahasiswa Prodi
        </a>
        <a class="nav-item @activeClass('ketua-prodi/bimbing*')" href="{{ route('ketua_prodi.bimbingan.index') }}">
            <i class="fas fa-users"></i> Mahasiswa Bimbing
        </a>
        <a class="nav-item @activeClass('ketua-prodi/logbook*')" href="{{ route('ketua_prodi.logbook.index') }}">
            <i class="fas fa-book-open"></i> Logbook
        </a>
        <a class="nav-item @activeClass('ketua-prodi/nilai*')" href="{{ route('ketua_prodi.nilai.index') }}">
            <i class="fas fa-star"></i> Nilai
        </a>
    </div>
    @endrole

    {{-- Menu Dosen (jika bukan chairman tapi punya is_ketua_prodi) --}}
    @role('dosen')
    @php $isKetuaProdi = auth()->user()->dosen && auth()->user()->dosen->is_ketua_prodi; @endphp
    @if($isKetuaProdi)
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('ketua-prodi/dashboard')" href="{{ route('ketua_prodi.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('ketua-prodi/mahasiswa*')" href="{{ route('ketua_prodi.mahasiswa.index') }}">
            <i class="fas fa-user-graduate"></i> Mahasiswa Prodi
        </a>
        <a class="nav-item @activeClass('ketua-prodi/bimbing*')" href="{{ route('ketua_prodi.bimbingan.index') }}">
            <i class="fas fa-users"></i> Mahasiswa Bimbingan
        </a>
        <a class="nav-item @activeClass('ketua-prodi/logbook*')" href="{{ route('ketua_prodi.logbook.index') }}">
            <i class="fas fa-book-open"></i> Logbook
        </a>
        <a class="nav-item @activeClass('ketua-prodi/nilai*')" href="{{ route('ketua_prodi.nilai.index') }}">
            <i class="fas fa-star"></i> Nilai
        </a>
    </div>
    @endif
    @endrole

    {{-- Menu Mitra --}}
    @role('mitra')
    <div class="nav-grp mt-1">
        <div class="nav-lbl">Menu Utama</div>
        <a class="nav-item @activeClass('mitra/dashboard')" href="{{ route('mitra.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-item @activeClass('mitra/mahasiswa*')" href="{{ route('mitra.mahasiswa.index') }}">
            <i class="fas fa-user-graduate"></i> Mahasiswa Magang
            @php $review = \App\Models\PengajuanMagang::where('mitra_id',auth()->user()->mitra?->id)->where('status','disetujui_koordinator')->count(); @endphp
            @if($review) <span class="nav-badge warn">{{ $review }}</span> @endif
        </a>
        <a class="nav-item @activeClass('mitra/penilaian*')" href="{{ route('mitra.penilaian.index') }}">
            <i class="fas fa-star"></i> Penilaian
        </a>
    </div>
    @endrole

    {{-- Footer User --}}
    <div class="sb-footer">
        <div class="dropdown">
            <div class="user-pill dropdown-toggle" data-bs-toggle="dropdown">
                <div class="u-avatar">
                    @if(auth()->user()->foto)
                        <img src="{{ Storage::url(auth()->user()->foto) }}" alt="">
                    @else
                        {{ auth()->user()->avatar_initials }}
                    @endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="u-name">{{ auth()->user()->name }}</div>
                    <div class="u-role">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? '') }}</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-dark mb-1" style="border-radius:10px;">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle me-2"></i>Profil Saya</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- MAIN WRAPPER --}}
<div class="main-wrap">
    {{-- Overlay for mobile --}}
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    {{-- Topbar --}}
    <div class="topbar">
        <button class="ic-btn menu-toggle me-2" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <div class="topbar-title">@yield('page-title','Dashboard')</div>
            @hasSection('page-sub')
                <div class="topbar-sub">@yield('page-sub')</div>
            @endif
        </div>
        <div class="tb-right">
            <div class="dropdown">
                <a href="#" class="ic-btn dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="fas fa-bell" style="font-size:16px;"></i>
                    @if(auth()->user()->notif_belum_dibaca > 0)
                        <span class="notif-dot"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0" style="width:320px;max-height:400px;overflow-y:auto;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.15);">
                    <div class="p-3 border-bottom bg-light">
                        <h6 class="mb-0 fw-bold">Notifikasi</h6>
                    </div>
                    @php
                        $notifs = auth()->user()->notifikasi()->orderByDesc('created_at')->take(10)->get();
                    @endphp
                    @if($notifs->count() > 0)
                        @foreach($notifs as $notif)
                            <a href="{{ $notif->url ?? '#' }}" class="dropdown-item border-bottom py-2 {{ $notif->dibaca ? '' : 'bg-light' }}">
                                <div class="d-flex gap-2">
                                    @switch($notif->tipe)
                                        @case('success')
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            @break
                                        @case('danger')
                                            <i class="fas fa-times-circle text-danger mt-1"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-warning mt-1"></i>
                                            @break
                                        @default
                                            <i class="fas fa-info-circle text-info mt-1"></i>
                                    @endswitch
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="small fw-semibold text-truncate">{{ $notif->judul }}</div>
                                        <div class="text-muted small text-truncate">{{ $notif->pesan }}</div>
                                        <div class="text-muted" style="font-size:10px;">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-bell-slash mb-2" style="font-size:24px;"></i>
                            <div class="small">Tidak ada notifikasi</div>
                        </div>
                    @endif
                </div>
            </div>
            @yield('topbar-actions')
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        {{-- Flash Messages as Toast --}}
        @if(session('success'))
            <div class="toast-notification toast-success" id="toast-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="toast-notification toast-error" id="toast-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// CSRF Token Setup untuk semua AJAX request
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Handle 419 Session Expired
$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
    if (jqXHR.status === 419) {
        // Session expired - redirect ke login
        window.location.href = '{{ route("login") }}?expired=1';
    }
});


$.fn.select2.defaults.set('theme','bootstrap-5');

// Toggle Sidebar for Mobile
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 991) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.querySelector('.menu-toggle');
        if (sidebar && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
                document.querySelector('.sidebar-overlay').classList.remove('show');
            }
        }
    }
});

$(document).ready(function(){
    $('.select2').select2();
    // Show toast notifications
    const toastSuccess = document.getElementById('toast-success');
    const toastError = document.getElementById('toast-error');
    if(toastSuccess){setTimeout(()=>toastSuccess.classList.add('show'),100);setTimeout(()=>{toastSuccess.classList.remove('show');setTimeout(()=>toastSuccess.remove(),300)},4000)}
    if(toastError){setTimeout(()=>toastError.classList.add('show'),100);setTimeout(()=>{toastError.classList.remove('show');setTimeout(()=>toastError.remove(),300)},5000)}
    // Auto-dismiss alerts
    setTimeout(()=>document.querySelectorAll('.alert').forEach(a=>{a.style.transition='opacity .5s';a.style.opacity='0';setTimeout(()=>a.remove(),500)}),4000)
});
</script>
@stack('scripts')
</body>
</html>

