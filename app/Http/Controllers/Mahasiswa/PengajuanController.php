<?php
// ============================================================
// app/Http/Controllers/Mahasiswa/PengajuanController.php
// ============================================================
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\StorePengajuanRequest;
use App\Models\{PengajuanMagang, Mitra, Notifikasi};
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuan = PengajuanMagang::with(['mitra','dosen'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')->paginate(10);

        return view('mahasiswa.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $mahasiswa     = Auth::user()->mahasiswa;
        $sedangMagang  = PengajuanMagang::where('mahasiswa_id',$mahasiswa->id)
            ->whereIn('status',['berjalan','diterima_mitra','diajukan','review_koordinator','disetujui_koordinator','review_mitra'])
            ->exists();

        abort_if($sedangMagang, 403, 'Anda masih memiliki pengajuan aktif.');

        $mitra = Mitra::where('status','aktif')->orderBy('nama_perusahaan')->get();
        return view('mahasiswa.pengajuan.create', compact('mitra'));
    }

    public function store(StorePengajuanRequest $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $data      = $request->validated();
        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['status']       = 'diajukan';

        if ($request->hasFile('surat_pengantar'))
            $data['surat_pengantar'] = $request->file('surat_pengantar')->store('pengajuan/surat','public');
        if ($request->hasFile('proposal'))
            $data['proposal'] = $request->file('proposal')->store('pengajuan/proposal','public');

        $pengajuan = PengajuanMagang::create($data);

        // Notifikasi admin/koordinator
        \App\Models\User::role('admin')->each(fn($admin) => Notifikasi::create([
            'user_id' => $admin->id,
            'judul'   => 'Pengajuan Magang Baru',
            'pesan'   => "{$mahasiswa->nama_lengkap} ({$mahasiswa->nim}) mengajukan magang ke {$pengajuan->mitra->nama_perusahaan}.",
            'tipe'    => 'info',
            'url'     => route('admin.pengajuan.show',$pengajuan),
        ]));

        return redirect()->route('mahasiswa.pengajuan.show',$pengajuan)
            ->with('success','Pengajuan berhasil dikirim. Menunggu review koordinator.');
    }

    public function show(PengajuanMagang $pengajuan)
    {
        // Manual authorization: ensure mahasiswa can only view their own pengajuan
        abort_unless(auth()->user()->mahasiswa->id === $pengajuan->mahasiswa_id, 403);
        $pengajuan->load(['mitra','dosen','logbook','penilaian','sertifikat']);
        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }

    public function downloadSurat(PengajuanMagang $pengajuan)
    {
        abort_unless(auth()->user()->mahasiswa->id === $pengajuan->mahasiswa_id, 403);
        return \Storage::disk('public')->download($pengajuan->surat_pengantar);
    }
}

