<?php
// ============================================================
// app/Http/Controllers/Admin/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Mahasiswa, Dosen, Mitra, PengajuanMagang, Penilaian};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mahasiswa'    => Mahasiswa::count(),
            'total_mitra'        => Mitra::where('status','aktif')->count(),
            'total_dosen'        => Dosen::count(),
            'magang_berjalan'    => PengajuanMagang::berjalan()->count(),
            'pengajuan_pending'  => PengajuanMagang::pending()->count(),
            'magang_selesai'     => PengajuanMagang::selesai()->count(),
        ];

        $pengajuan_terbaru = PengajuanMagang::with(['mahasiswa','mitra'])
            ->orderByDesc('created_at')->take(8)->get();

        $mahasiswa_berjalan = PengajuanMagang::with(['mahasiswa','mitra','dosen'])
            ->berjalan()->orderByDesc('created_at')->take(5)->get();

        // Grafik pengajuan 6 bulan terakhir
        $grafik = collect(range(5,0))->map(function($i) {
            $bulan = now()->subMonths($i);
            return [
                'bulan'  => $bulan->format('M Y'),
                'jumlah' => PengajuanMagang::whereYear('created_at',$bulan->year)
                                           ->whereMonth('created_at',$bulan->month)->count(),
            ];
        });

        // Per prodi
        $per_prodi = Mahasiswa::selectRaw('program_studi, COUNT(*) as total')
            ->groupBy('program_studi')->orderByDesc('total')->take(5)->get();

        return view('admin.dashboard.index', compact('stats','pengajuan_terbaru','mahasiswa_berjalan','grafik','per_prodi'));
    }
}