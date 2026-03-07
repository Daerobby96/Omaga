<?php
// ============================================================
// app/Http/Controllers/Dosen/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanMagang, Logbook};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        $bimbingan = PengajuanMagang::with(['mahasiswa','mitra'])
            ->where('dosen_id', $dosen->id)->orderByDesc('created_at')->get();

        $stats = [
            'total_bimbingan' => $bimbingan->count(),
            'aktif'           => $bimbingan->whereIn('status',['berjalan','diterima_mitra'])->count(),
            'selesai'         => $bimbingan->where('status','selesai')->count(),
            'belum_dinilai'   => $bimbingan->where('status','selesai')->filter(fn($p) => !$p->penilaian?->dosenSudahNilai())->count(),
        ];

        $logbook_pending = Logbook::whereHas('pengajuan', fn($q) => $q->where('dosen_id',$dosen->id))
            ->where('status','submitted')->orderByDesc('created_at')->take(10)->get();

        return view('dosen.dashboard.index', compact('dosen','bimbingan','stats','logbook_pending'));
    }
}