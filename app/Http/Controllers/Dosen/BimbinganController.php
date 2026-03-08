<?php
// ============================================================
// app/Http/Controllers/Dosen/BimbinganController.php
// Controller untuk halaman bimbingan dosen
// ============================================================
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    /**
     * List mahasiswa bimbingan
     */
    public function index()
    {
        $dosen = auth()->user()->dosen;
        
        $pengajuan = PengajuanMagang::with(['mahasiswa.user', 'mitra'])
            ->where('dosen_id', $dosen->id)
            ->whereIn('status', ['disetujui_koordinator', 'diterima_mitra', 'berjalan'])
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('dosen.bimbingan.index', compact('pengajuan'));
    }

    /**
     * Detail mahasiswa bimbingan
     */
    public function show(PengajuanMagang $pengajuan)
    {
        $dosen = auth()->user()->dosen;
        
        // Verifikasi dosen pembimbing
        abort_unless($pengajuan->dosen_id === $dosen->id, 403);
        
        $pengajuan->load(['mahasiswa.user', 'mitra', 'logbook', 'penilaian']);
        
        return view('dosen.bimbingan.show', compact('pengajuan'));
    }
}
