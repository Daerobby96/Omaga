<?php
// ============================================================
// app/Http/Controllers/Mahasiswa/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanMagang, Logbook};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa      = Auth::user()->mahasiswa;
        $pengajuan      = PengajuanMagang::with(['mitra','dosen'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')->get();
        $pengajuanAktif = $pengajuan->whereIn('status',['berjalan','diterima_mitra'])->first();
        $logbookTerbaru = $pengajuanAktif
            ? Logbook::where('pengajuan_id',$pengajuanAktif->id)->orderByDesc('tanggal')->take(5)->get()
            : collect();

        $stats = [
            'total_pengajuan' => $pengajuan->count(),
            'berjalan'        => $pengajuan->where('status','berjalan')->count(),
            'selesai'         => $pengajuan->where('status','selesai')->count(),
            'total_logbook'   => $pengajuanAktif ? Logbook::where('pengajuan_id',$pengajuanAktif->id)->count() : 0,
        ];

        return view('mahasiswa.dashboard.index', compact('mahasiswa','pengajuan','pengajuanAktif','logbookTerbaru','stats'));
    }
}