<?php
// ============================================================
// app/Http/Controllers/Mitra/MahasiswaController.php
// Mitra review & terima/tolak mahasiswa, serta lihat logbook
// ============================================================
namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanMagang, Notifikasi};
use App\Mail\{PengajuanDiterima, PengajuanDitolak};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mitra = auth()->user()->mitra;
        $pengajuan = PengajuanMagang::with(['mahasiswa','dosen'])
            ->where('mitra_id',$mitra->id)
            ->orderByDesc('created_at')->paginate(15);
        return view('mitra.mahasiswa.index', compact('pengajuan'));
    }

    public function show(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        $pengajuan->load(['mahasiswa.user','dosen','logbook','penilaian']);
        return view('mitra.mahasiswa.show', compact('pengajuan'));
    }

    /** Mitra menerima mahasiswa magang */
    public function terima(Request $request, PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        abort_unless($pengajuan->status === 'disetujui_koordinator', 403);

        $pengajuan->update([
            'status'           => 'diterima_mitra',
            'catatan_mitra'    => $request->catatan_mitra,
            'diterima_mitra_at'=> now(),
        ]);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Diterima Perusahaan Mitra',
            'pesan'   => "Selamat! Anda diterima magang di {$pengajuan->mitra->nama_perusahaan}. Magang akan segera dimulai.",
            'tipe'    => 'success',
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(new PengajuanDiterima($pengajuan));

        // Notif admin
        \App\Models\User::role('admin')->each(fn($admin) => Notifikasi::create([
            'user_id' => $admin->id,
            'judul'   => 'Mitra Terima Mahasiswa',
            'pesan'   => "{$pengajuan->mitra->nama_perusahaan} menerima {$pengajuan->mahasiswa->nama_lengkap}. Aktifkan status magang.",
            'tipe'    => 'info',
            'url'     => route('admin.pengajuan.show',$pengajuan),
        ]));

        return back()->with('success','Mahasiswa berhasil diterima.');
    }

    /** Mitra menolak mahasiswa */
    public function tolak(Request $request, PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        $request->validate(['catatan_mitra'=>'required|string|max:500']);

        $pengajuan->update([
            'status'        => 'ditolak_mitra',
            'catatan_mitra' => $request->catatan_mitra,
        ]);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Pengajuan Ditolak Mitra',
            'pesan'   => "Maaf, {$pengajuan->mitra->nama_perusahaan} tidak dapat menerima Anda saat ini.",
            'tipe'    => 'danger',
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(
            new PengajuanDitolak($pengajuan, $request->catatan_mitra)
        );

        return back()->with('success','Mahasiswa telah ditolak.');
    }

    /** Mitra mengatur periode magang */
    public function editPeriode(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        abort_unless($pengajuan->status === 'disetujui_koordinator', 403);
        
        return view('mitra.mahasiswa.edit-periode', compact('pengajuan'));
    }

    /** Mitra update periode magang */
    public function updatePeriode(Request $request, PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        abort_unless($pengajuan->status === 'disetujui_koordinator', 403);

        $request->validate([
            'tanggal_mulai'    => 'required|date|after_or_equal:today',
            'tanggal_selesai'  => 'required|date|after:tanggal_mulai',
        ]);

        $pengajuan->update([
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Periode Magang Diperbarui',
            'pesan'   => "Periode magang Anda di {$pengajuan->mitra->nama_perusahaan} telah diperbarui.",
            'tipe'    => 'info',
        ]);

        return redirect()->route('mitra.dashboard')->with('success', 'Periode magang berhasil diperbarui.');
    }
}

