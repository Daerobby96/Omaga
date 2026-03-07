<?php

// ============================================================
// app/Http/Controllers/KetuaProdi/DashboardController.php
// ============================================================
namespace App\Http\Controllers\KetuaProdi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\PengajuanMagang;
use App\Models\Logbook;
use App\Models\Penilaian;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        // Statistik mahasiswa prodi
        $totalMahasiswa = Mahasiswa::where('program_studi', $prodi)->count();
        $mahasiswaAktif = Mahasiswa::where('program_studi', $prodi)
            ->where('status_akademik', 'aktif')->count();

        // Mahasiswa yang sedang magang
        $pengajuanAktif = PengajuanMagang::whereHas('mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->whereIn('status', ['berjalan', 'diterima_mitra'])->count();

        // Riwayat magang
        $pengajuanSelesai = PengajuanMagang::whereHas('mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->where('status', 'selesai')->count();

        // Statistik logbook bulan ini
        $logbookBulanIni = Logbook::whereHas('pengajuan.mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->whereMonth('tanggal', now()->month)
          ->whereYear('tanggal', now()->year)->count();

        // Rata-rata nilai
        $nilaiRataRata = Penilaian::whereHas('pengajuan.mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->whereNotNull('nilai_akhir')->avg('nilai_akhir');

        return view('ketua_prodi.dashboard.index', compact(
            'prodi', 'totalMahasiswa', 'mahasiswaAktif', 'pengajuanAktif', 
            'pengajuanSelesai', 'logbookBulanIni', 'nilaiRataRata'
        ));
    }

    public function prodi()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        $prodis = \App\Models\Prodi::where('nama_prodi', $prodi)->get();

        return view('ketua_prodi.prodi.index', compact('prodis', 'prodi'));
    }

    public function mahasiswa()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        $mahasiswa = Mahasiswa::where('program_studi', $prodi)
            ->orderBy('nama_lengkap')->paginate(20);

        return view('ketua_prodi.mahasiswa.index', compact('mahasiswa', 'prodi'));
    }

    public function logbook()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        $logbook = Logbook::whereHas('pengajuan.mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->orderByDesc('tanggal')->paginate(20);

        return view('ketua_prodi.logbook.index', compact('logbook', 'prodi'));
    }

    public function pengajuan()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        $pengajuan = PengajuanMagang::whereHas('mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->orderByDesc('created_at')->paginate(20);

        return view('ketua_prodi.pengajuan.index', compact('pengajuan', 'prodi'));
    }

    public function nilai()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        $penilaian = Penilaian::whereHas('pengajuan.mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->with(['pengajuan.mahasiswa', 'pengajuan.mitra'])->orderByDesc('created_at')->paginate(20);

        return view('ketua_prodi.nilai.index', compact('penilaian', 'prodi'));
    }

    public function laporan()
    {
        $dosen = Auth::user()->dosen;
        $prodi = $dosen->prodi_yang_dikelola;

        abort_unless($prodi, 403, 'Anda belum ditunjuk sebagai Ketua Program Studi.');

        // Data untuk laporan
        $pengajuanByStatus = PengajuanMagang::whereHas('mahasiswa', function($q) use ($prodi) {
            $q->where('program_studi', $prodi);
        })->selectRaw("status, COUNT(*) as total")->groupBy('status')->get();

        $mahasiswaByAngkatan = Mahasiswa::where('program_studi', $prodi)
            ->selectRaw("angkatan, COUNT(*) as total")->groupBy('angkatan')->orderByDesc('angkatan')->get();

        return view('ketua_prodi.laporan.index', compact('prodi', 'pengajuanByStatus', 'mahasiswaByAngkatan'));
    }

    // === Mahasiswa Bimbing ===
    public function bimbingan()
    {
        $dosen = Auth::user()->dosen;
        
        // Mahasiswa yang dibimbing oleh dosen ini
        $bimbingan = PengajuanMagang::where('dosen_id', $dosen->id)
            ->whereIn('status', ['berjalan', 'diterima_mitra', 'selesai'])
            ->with(['mahasiswa', 'mitra'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('ketua_prodi.bimbingan.index', compact('bimbingan', 'dosen'));
    }

    public function logbookBimbing()
    {
        $dosen = Auth::user()->dosen;
        
        // Logbook dari mahasiswa yang dibimbing
        $logbook = Logbook::whereHas('pengajuan', function($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->orderByDesc('tanggal')->paginate(20);

        return view('ketua_prodi.bimbing.logbook', compact('logbook', 'dosen'));
    }

    public function setujuLogbookBimbing(Logbook $logbook)
    {
        $dosen = Auth::user()->dosen;
        
        if ($logbook->pengajuan->dosen_id != $dosen->id) {
            abort(403, 'Anda bukan pembimbing mahasiswa ini.');
        }
        
        $logbook->update(['status' => 'approved']);
        return back()->with('success', 'Logbook disetujui.');
    }

    public function revisiLogbookBimbing(Request $request, Logbook $logbook)
    {
        $dosen = Auth::user()->dosen;
        
        if ($logbook->pengajuan->dosen_id != $dosen->id) {
            abort(403, 'Anda bukan pembimbing mahasiswa ini.');
        }
        
        $logbook->update([
            'status' => 'revision',
            'catatan' => $request->catatan
        ]);
        return back()->with('success', 'Logbook dikembalikan untuk revisi.');
    }
}
