<?php

// ============================================================
// app/Http/Controllers/Dosen/PenilaianController.php
// ============================================================
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanMagang;
use App\Models\Penilaian;
use App\Models\Notifikasi;
use App\Http\Requests\Requests\StorePenilaianDosenRequest;
use App\Mail\PenilaianMasuk;
use Illuminate\Support\Facades\Mail;

class PenilaianController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $penilaian = Penilaian::with(['mahasiswa','pengajuan.mitra'])
            ->whereHas('pengajuan', fn($q) => $q->where('dosen_id',$dosen->id))
            ->orderByDesc('created_at')->paginate(15);
        return view('dosen.penilaian.index', compact('penilaian'));
    }

    public function create(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->dosen_id === Auth::user()->dosen->id, 403);
        abort_unless($pengajuan->status === 'selesai', 403);
        $penilaian = $pengajuan->penilaian ?? new Penilaian();
        return view('dosen.penilaian.create', compact('pengajuan','penilaian'));
    }

    public function store(StorePenilaianDosenRequest $request, PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->dosen_id === Auth::user()->dosen->id, 403);

        $penilaian = Penilaian::firstOrNew(['pengajuan_id'=>$pengajuan->id]);
        $penilaian->fill($request->validated());
        $penilaian->mahasiswa_id    = $pengajuan->mahasiswa_id;
        $penilaian->dinilai_dosen_at = now();

        // Hitung nilai akhir jika mitra sudah menilai
        if ($penilaian->mitraSudahNilai()) {
            $penilaian->nilai_akhir = $penilaian->hitungNilaiAkhir();
            $penilaian->grade       = $penilaian->hitungGrade($penilaian->nilai_akhir);
            $penilaian->lulus       = $penilaian->nilai_akhir >= 60;
        }

        $penilaian->save();

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Penilaian Dosen Masuk',
            'pesan'   => 'Dosen pembimbing telah memberikan penilaian magang Anda.',
            'tipe'    => 'info',
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(
            new PenilaianMasuk($pengajuan, $penilaian, 'dosen')
        );

        return redirect()->route('dosen.penilaian.index')->with('success','Penilaian berhasil disimpan.');
    }
}
