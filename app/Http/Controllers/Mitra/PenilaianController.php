<?php


// ============================================================
// app/Http/Controllers/Mitra/PenilaianController.php
// ============================================================
namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use App\Models\Penilaian;
use App\Models\Notifikasi;
use App\Http\Requests\Requests\StorePenilaianMitraRequest;

class PenilaianController extends Controller
{
    public function index()
    {
        $mitra = auth()->user()->mitra;
        $penilaian = Penilaian::with(['pengajuan.mahasiswa'])
            ->whereHas('pengajuan', fn($q) => $q->where('mitra_id', $mitra->id))
            ->orderByDesc('created_at')->paginate(20);
        return view('mitra.penilaian.index', compact('penilaian'));
    }

    public function create(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);
        abort_unless($pengajuan->status === 'selesai', 403);
        $penilaian = $pengajuan->penilaian ?? new Penilaian();
        return view('mitra.penilaian.create', compact('pengajuan','penilaian'));
    }

    public function store(StorePenilaianMitraRequest $request, PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->mitra_id === auth()->user()->mitra->id, 403);

        $penilaian = Penilaian::firstOrNew(['pengajuan_id'=>$pengajuan->id]);
        $penilaian->fill($request->validated());
        $penilaian->mahasiswa_id    = $pengajuan->mahasiswa_id;
        $penilaian->dinilai_mitra_at = now();

        if ($penilaian->dosenSudahNilai()) {
            $penilaian->nilai_akhir = $penilaian->hitungNilaiAkhir();
            $penilaian->grade       = $penilaian->hitungGrade($penilaian->nilai_akhir);
            $penilaian->lulus       = $penilaian->nilai_akhir >= 60;
        }

        $penilaian->save();

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Penilaian Mitra Masuk',
            'pesan'   => "{$pengajuan->mitra->nama_perusahaan} telah memberikan penilaian untuk magang Anda.",
            'tipe'    => 'info',
        ]);

        return redirect()->route('mitra.dashboard')->with('success','Penilaian berhasil disimpan.');
    }
}
