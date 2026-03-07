<?php
// ============================================================
// app/Http/Controllers/Dosen/LogbookController.php
// ============================================================
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\{Logbook, PengajuanMagang, Notifikasi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $dosen   = Auth::user()->dosen;
        $logbook = Logbook::with(['mahasiswa','pengajuan.mitra'])
            ->whereHas('pengajuan', fn($q) => $q->where('dosen_id',$dosen->id))
            ->when($request->status, fn($q,$v) => $q->where('status',$v))
            ->when($request->mahasiswa_id, fn($q,$v) => $q->where('mahasiswa_id',$v))
            ->orderByDesc('tanggal')->paginate(20)->withQueryString();

        $mahasiswaBimbingan = PengajuanMagang::with('mahasiswa')
            ->where('dosen_id',$dosen->id)->berjalan()->get()->pluck('mahasiswa');

        return view('dosen.logbook.index', compact('logbook','mahasiswaBimbingan'));
    }

    public function setujui(Logbook $logbook)
    {
        $this->authorizeDosen($logbook);
        $logbook->update(['status'=>'disetujui','disetujui_at'=>now()]);

        Notifikasi::create([
            'user_id' => $logbook->mahasiswa->user_id,
            'judul'   => 'Logbook Disetujui',
            'pesan'   => "Logbook tanggal {$logbook->tanggal->format('d M Y')} telah disetujui dosen pembimbing.",
            'tipe'    => 'success',
        ]);

        return back()->with('success','Logbook disetujui.');
    }

    public function revisi(Request $request, Logbook $logbook)
    {
        $this->authorizeDosen($logbook);
        $request->validate(['catatan_dosen'=>'required|string|max:500']);
        $logbook->update(['status'=>'revisi','catatan_dosen'=>$request->catatan_dosen]);

        Notifikasi::create([
            'user_id' => $logbook->mahasiswa->user_id,
            'judul'   => 'Logbook Perlu Revisi',
            'pesan'   => "Logbook {$logbook->tanggal->format('d M Y')} perlu direvisi: {$request->catatan_dosen}",
            'tipe'    => 'warning',
        ]);

        return back()->with('success','Logbook dikembalikan untuk revisi.');
    }

    private function authorizeDosen(Logbook $logbook)
    {
        abort_unless($logbook->pengajuan->dosen_id === Auth::user()->dosen->id, 403);
    }
}


