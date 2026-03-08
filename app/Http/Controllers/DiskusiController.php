<?php
// ============================================================
// app/Http/Controllers/DiskusiController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\Diskusi;
use App\Models\PengajuanMagang;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiskusiController extends Controller
{
    /**
     * Tampilkan forum diskusi untuk pengajuan tertentu
     */
    public function index(PengajuanMagang $pengajuan)
    {
        // Cek akses: mahasiswa pemilik, dosen pembimbing, atau mitra
        $user = Auth::user();
        
        $isAuthorized = 
            $pengajuan->mahasiswa->user_id === $user->id ||
            ($pengajuan->dosen && $pengajuan->dosen->user_id === $user->id) ||
            $pengajuan->mitra->user_id === $user->id ||
            $user->hasRole('admin');

        abort_unless($isAuthorized, 403);

        $diskusi = Diskusi::forPengajuan($pengajuan->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        $pengajuan->load(['mahasiswa', 'dosen', 'mitra']);

        return view('diskusi.index', compact('pengajuan', 'diskusi'));
    }

    /**
     * Simpan pesan diskusi baru
     */
    public function store(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'isi' => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:diskusi,id',
        ]);

        // Cek akses
        $user = Auth::user();
        $isAuthorized = 
            $pengajuan->mahasiswa->user_id === $user->id ||
            ($pengajuan->dosen && $pengajuan->dosen->user_id === $user->id) ||
            $pengajuan->mitra->user_id === $user->id ||
            $user->hasRole('admin');

        abort_unless($isAuthorized, 403);

        $diskusi = Diskusi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'isi' => $request->isi,
        ]);

        // Kirim notifikasi ke pihak-pihak terkait
        $this->kirimNotifikasi($pengajuan, $user, $request->parent_id);

        return back()->with('success', 'Pesan diskusi berhasil dikirim.');
    }

    /**
     * Kirim notifikasi ke pihak terkait
     */
    private function kirimNotifikasi(PengajuanMagang $pengajuan, $user, ?int $parentId = null)
    {
        // Jika reply, notify parent author
        if ($parentId) {
            $parent = Diskusi::find($parentId);
            if ($parent && $parent->user_id !== $user->id) {
                Notifikasi::create([
                    'user_id' => $parent->user_id,
                    'judul'   => 'Balasan Diskusi Baru',
                    ' pesan'  => "{$user->name} membalas diskusi Anda.",
                    'tipe'    => 'info',
                    'url'     => route('diskusi.index', $pengajuan),
                ]);
            }
        }

        // Notify all participants in this pengajuan except sender
        $participantIds = [
            $pengajuan->mahasiswa->user_id,
            $pengajuan->mitra->user_id,
        ];
        
        if ($pengajuan->dosen) {
            $participantIds[] = $pengajuan->dosen->user_id;
        }

        foreach ($participantIds as $id) {
            if ($id !== $user->id) {
                // Avoid duplicate notifications
                Notifikasi::firstOrCreate([
                    'user_id' => $id,
                    'judul'   => 'Diskusi Baru di Magang Anda',
                    ' pesan'  => "{$user->name} mengirim pesan diskusi baru.",
                    'url'     => route('diskusi.index', $pengajuan),
                ], [
                    'tipe' => 'info',
                ]);
            }
        }
    }
}
