<?php
// ============================================================
// routes/web.php
// ============================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{LoginController, RegisterMitraController, RegisterMahasiswaController};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    MahasiswaController, DosenController, MitraController,
    PengajuanController, SertifikatController, LaporanController,
    ProdiController
};
use App\Http\Controllers\Mahasiswa\{
    DashboardController as MahasiswaDashboard,
    PengajuanController as MahasiswaPengajuan,
    LogbookController as MahasiswaLogbook
};
use App\Http\Controllers\Dosen\{
    DashboardController as DosenDashboard,
    LogbookController as DosenLogbook,
    PenilaianController as DosenPenilaian
};
use App\Http\Controllers\Mitra\{
    DashboardController as MitraDashboard,
    MahasiswaController as MitraMahasiswa,
    PenilaianController as MitraPenilaian
};
use App\Http\Controllers\KetuaProdi\DashboardController as KetuaProdiDashboard;

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',                [LoginController::class,'showLoginForm'])->name('login');
    Route::post('/login',               [LoginController::class,'login']);
    Route::get('/daftar-mitra',         [RegisterMitraController::class,'showForm'])->name('mitra.register');
    Route::post('/daftar-mitra',        [RegisterMitraController::class,'register']);
    Route::get('/daftar-mahasiswa',     [RegisterMahasiswaController::class,'showForm'])->name('mahasiswa.register');
    Route::post('/daftar-mahasiswa',    [RegisterMahasiswaController::class,'register']);
});

Route::post('/logout', [LoginController::class,'logout'])->name('logout')->middleware('auth');

// ── Root redirect ─────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) return redirect()->route(auth()->user()->getDashboardRoute());
    return redirect()->route('login');
})->name('home');

// ── ADMIN ─────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class,'index'])->name('dashboard');

    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);

    // Dosen
    Route::resource('dosen', DosenController::class)->except(['edit','update','destroy']);
    Route::get('dosen/{dosen}/edit',    [DosenController::class,'edit'])->name('dosen.edit');
    Route::put('dosen/{dosen}',         [DosenController::class,'update'])->name('dosen.update');
    Route::delete('dosen/{dosen}',      [DosenController::class,'destroy'])->name('dosen.destroy');

    // Program Studi
    Route::resource('prodi', ProdiController::class);

    // Mitra
    Route::resource('mitra', MitraController::class);
    Route::patch('mitra/{mitra}/aktivasi', [MitraController::class,'aktivasi'])->name('mitra.aktivasi');

    // Pengajuan
    Route::resource('pengajuan', PengajuanController::class)->only(['index','show']);
    Route::patch('pengajuan/{pengajuan}/setujui', [PengajuanController::class,'setujui'])->name('pengajuan.setujui');
    Route::patch('pengajuan/{pengajuan}/tolak',   [PengajuanController::class,'tolak'])->name('pengajuan.tolak');
    Route::patch('pengajuan/{pengajuan}/mulai',   [PengajuanController::class,'mulai'])->name('pengajuan.mulai');
    Route::patch('pengajuan/{pengajuan}/selesai', [PengajuanController::class,'selesai'])->name('pengajuan.selesai');

    // Sertifikat
    Route::get('sertifikat',                               [SertifikatController::class,'index'])->name('sertifikat.index');
    Route::get('sertifikat/settings',                     [SertifikatController::class,'settings'])->name('sertifikat.settings');
    Route::post('sertifikat/settings',                   [SertifikatController::class,'updateSettings'])->name('sertifikat.settings.update');
    Route::post('sertifikat/{pengajuan}/generate',         [SertifikatController::class,'generate'])->name('sertifikat.generate');
    Route::get('sertifikat/{sertifikat}/view',             [SertifikatController::class,'view'])->name('sertifikat.view');
    Route::get('sertifikat/{sertifikat}/download',         [SertifikatController::class,'download'])->name('sertifikat.download');
    Route::get('sertifikat/{sertifikat}/download-image',   [SertifikatController::class,'downloadImage'])->name('sertifikat.downloadImage');

    // Laporan
    Route::get('laporan',           [LaporanController::class,'index'])->name('laporan.index');
    Route::get('laporan/export-pdf',[LaporanController::class,'exportPdf'])->name('laporan.export-pdf');
});

// ── MAHASISWA ─────────────────────────────────────────────────
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth','role:mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaDashboard::class,'index'])->name('dashboard');

    // Pengajuan
    Route::resource('pengajuan', MahasiswaPengajuan::class)->only(['index','create','store','show']);
    Route::get('pengajuan/{pengajuan}/surat', [MahasiswaPengajuan::class,'downloadSurat'])->name('pengajuan.surat');

    // Logbook
    Route::resource('logbook', MahasiswaLogbook::class)->only(['index','create','store','show','edit','update']);

    // Penilaian & Sertifikat (read only)
    Route::get('penilaian',           fn() => view('mahasiswa.penilaian.index'))->name('penilaian.index');
    Route::get('sertifikat/{sertifikat}/view',     [SertifikatController::class,'view'])->name('sertifikat.view');
    Route::get('sertifikat/{sertifikat}/download', [SertifikatController::class,'download'])->name('sertifikat.download');
    Route::get('sertifikat/{sertifikat}/download-image', [SertifikatController::class,'downloadImage'])->name('sertifikat.downloadImage');
});

// ── DOSEN ─────────────────────────────────────────────────────
Route::prefix('dosen')->name('dosen.')->middleware(['auth','role:dosen'])->group(function () {
    Route::get('/dashboard', [DosenDashboard::class,'index'])->name('dashboard');

    // Bimbingan (read only)
    Route::get('bimbingan',        fn() => view('dosen.bimbingan.index'))->name('bimbingan.index');
    Route::get('bimbingan/{pengajuan}', fn($p) => view('dosen.bimbingan.show',['pengajuan'=>$p]))->name('bimbingan.show');

    // Logbook
    Route::get('logbook',                              [DosenLogbook::class,'index'])->name('logbook.index');
    Route::patch('logbook/{logbook}/setujui',          [DosenLogbook::class,'setujui'])->name('logbook.setujui');
    Route::patch('logbook/{logbook}/revisi',           [DosenLogbook::class,'revisi'])->name('logbook.revisi');

    // Penilaian
    Route::get('penilaian',                            [DosenPenilaian::class,'index'])->name('penilaian.index');
    Route::get('penilaian/{pengajuan}/create',         [DosenPenilaian::class,'create'])->name('penilaian.create');
    Route::post('penilaian/{pengajuan}',               [DosenPenilaian::class,'store'])->name('penilaian.store');
});

// ── MITRA ─────────────────────────────────────────────────────
Route::prefix('mitra')->name('mitra.')->middleware(['auth','role:mitra'])->group(function () {
    Route::get('/dashboard', [MitraDashboard::class,'index'])->name('dashboard');

    // Mahasiswa
    Route::get('mahasiswa',                        [MitraMahasiswa::class,'index'])->name('mahasiswa.index');
    Route::get('mahasiswa/{pengajuan}',            [MitraMahasiswa::class,'show'])->name('mahasiswa.show');
    Route::patch('mahasiswa/{pengajuan}/terima',   [MitraMahasiswa::class,'terima'])->name('mahasiswa.terima');
    Route::patch('mahasiswa/{pengajuan}/tolak',    [MitraMahasiswa::class,'tolak'])->name('mahasiswa.tolak');

    // Penilaian
    Route::get('penilaian', [MitraPenilaian::class,'index'])->name('penilaian.index');
    Route::get('penilaian/{pengajuan}/create',  [MitraPenilaian::class,'create'])->name('penilaian.create');
    Route::post('penilaian/{pengajuan}',        [MitraPenilaian::class,'store'])->name('penilaian.store');
});

// ── KETUA PRODI ────────────────────────────────────────────────
Route::prefix('ketua-prodi')->name('ketua_prodi.')->middleware(['auth', 'ketua_prodi'])->group(function () {
    Route::get('/dashboard', [KetuaProdiDashboard::class,'index'])->name('dashboard');

    // Program Studi
    Route::get('prodi', [KetuaProdiDashboard::class,'prodi'])->name('prodi.index');

    // Mahasiswa
    Route::get('mahasiswa', [KetuaProdiDashboard::class,'mahasiswa'])->name('mahasiswa.index');

    // Bimbingan (mahasiswa yang dibimbing)
    Route::get('bimbingan', [KetuaProdiDashboard::class,'bimbingan'])->name('bimbingan.index');

    // Logbook
    Route::get('logbook', [KetuaProdiDashboard::class,'logbook'])->name('logbook.index');

    // Pengajuan
    Route::get('pengajuan', [KetuaProdiDashboard::class,'pengajuan'])->name('pengajuan.index');

    // Nilai
    Route::get('nilai', [KetuaProdiDashboard::class,'nilai'])->name('nilai.index');

    // Laporan
    Route::get('laporan', [KetuaProdiDashboard::class,'laporan'])->name('laporan.index');
});

// ── Notifikasi (semua role) ───────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('notifikasi', function () {
        $notif = auth()->user()->notifikasi()->orderByDesc('created_at')->paginate(20);
        auth()->user()->notifikasi()->where('dibaca',false)->update(['dibaca'=>true,'dibaca_at'=>now()]);
        return view('components.notifikasi', compact('notif'));
    })->name('notifikasi.index');
});

// ── Profile (semua role) ───────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('profile', [\App\Http\Controllers\ProfileController::class,'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class,'update'])->name('profile.update');
});