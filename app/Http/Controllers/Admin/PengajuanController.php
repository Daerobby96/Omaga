<?php
// ============================================================
// app/Http/Controllers/Admin/PengajuanController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanMagang, Dosen, Notifikasi};
use App\Mail\{PengajuanDiterima, PengajuanDitolak};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanMagang::with(['mahasiswa','mitra','dosen'])
            ->when($request->search, fn($q,$s) =>
                $q->whereHas('mahasiswa', fn($q2) =>
                    $q2->where('nama_lengkap','like',"%$s%")->orWhere('nim','like',"%$s%")
                )
            )
            ->when($request->status, fn($q,$v) => $q->where('status',$v))
            ->when($request->mitra,  fn($q,$v) => $q->where('mitra_id',$v));

        $pengajuan     = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $statusOptions = array_keys(PengajuanMagang::STATUS_LABELS);

        return view('admin.pengajuan.index', compact('pengajuan','statusOptions'));
    }

    public function show(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa.user','mitra','dosen','logbook','penilaian','sertifikat']);
        $dosen_tersedia = Dosen::whereRaw(
            '(kuota_bimbingan - (SELECT COUNT(*) FROM pengajuan_magang WHERE dosen_id = dosen.id AND status IN ("berjalan","diterima_mitra"))) > 0'
        )->get();

        return view('admin.pengajuan.show', compact('pengajuan','dosen_tersedia'));
    }

    /** Koordinator menyetujui pengajuan + assign dosen */
    public function setujui(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'dosen_id'            => 'required|exists:dosen,id',
            'catatan_koordinator' => 'nullable|string|max:500',
        ]);

        $pengajuan->update([
            'status'                      => 'disetujui_koordinator',
            'dosen_id'                    => $request->dosen_id,
            'catatan_koordinator'         => $request->catatan_koordinator,
            'disetujui_koordinator_at'    => now(),
            'disetujui_oleh'              => auth()->id(),
        ]);

        // Notifikasi mahasiswa (in-app)
        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Pengajuan Disetujui Koordinator',
            'pesan'   => "Pengajuan magang Anda di {$pengajuan->mitra->nama_perusahaan} telah disetujui. Menunggu konfirmasi mitra.",
            'tipe'    => 'success',
            'url'     => route('mahasiswa.pengajuan.show', $pengajuan),
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(new PengajuanDiterima($pengajuan));

        // Notifikasi mitra
        Notifikasi::create([
            'user_id' => $pengajuan->mitra->user_id,
            'judul'   => 'Permohonan Magang Baru',
            'pesan'   => "Ada mahasiswa yang mengajukan magang ke perusahaan Anda. Silakan review.",
            'tipe'    => 'info',
            'url'     => route('mitra.mahasiswa.show', $pengajuan),
        ]);

        return back()->with('success','Pengajuan disetujui dan diteruskan ke mitra.');
    }

    /** Koordinator menolak pengajuan */
    public function tolak(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate(['catatan_koordinator' => 'required|string|max:500']);

        $pengajuan->update([
            'status'              => 'ditolak_koordinator',
            'catatan_koordinator' => $request->catatan_koordinator,
        ]);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Pengajuan Ditolak',
            'pesan'   => "Pengajuan magang Anda ditolak. Alasan: {$request->catatan_koordinator}",
            'tipe'    => 'danger',
            'url'     => route('mahasiswa.pengajuan.show', $pengajuan),
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(
            new PengajuanDitolak($pengajuan, $request->catatan_koordinator)
        );

        return back()->with('success','Pengajuan telah ditolak.');
    }

    /** Admin menandai magang mulai berjalan */
    public function mulai(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->status === 'diterima_mitra', 403);

        $pengajuan->update(['status'=>'berjalan']);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Magang Dimulai',
            'pesan'   => "Selamat! Magang Anda di {$pengajuan->mitra->nama_perusahaan} resmi dimulai. Silakan isi logbook harian.",
            'tipe'    => 'success',
        ]);

        return back()->with('success','Status magang diperbarui menjadi Berjalan.');
    }

    /** Admin menandai magang selesai & trigger penilaian */
    public function selesai(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->status === 'berjalan', 403);

        $pengajuan->update(['status'=>'selesai']);

        // Buat record penilaian kosong
        \App\Models\Penilaian::firstOrCreate(['pengajuan_id'=>$pengajuan->id,'mahasiswa_id'=>$pengajuan->mahasiswa_id]);

        Notifikasi::create([
            'user_id' => $pengajuan->dosen->user_id,
            'judul'   => 'Magang Selesai - Silakan Nilai',
            'pesan'   => "Magang mahasiswa {$pengajuan->mahasiswa->nama_lengkap} telah selesai. Silakan berikan penilaian.",
            'tipe'    => 'info',
            'url'     => route('dosen.penilaian.create', $pengajuan),
        ]);

        Notifikasi::create([
            'user_id' => $pengajuan->mitra->user_id,
            'judul'   => 'Magang Selesai - Silakan Nilai',
            'pesan'   => "Magang mahasiswa {$pengajuan->mahasiswa->nama_lengkap} telah selesai. Silakan berikan penilaian.",
            'tipe'    => 'info',
            'url'     => route('mitra.penilaian.create', $pengajuan),
        ]);

        return back()->with('success','Magang ditandai selesai. Dosen dan mitra dapat memberikan penilaian.');
    }
}