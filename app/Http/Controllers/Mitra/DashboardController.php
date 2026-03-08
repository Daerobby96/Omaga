<?php

// ============================================================
// app/Http/Controllers/Mitra/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Mitra;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mitra = auth()->user()->mitra;
        $pengajuan = \App\Models\PengajuanMagang::with(['mahasiswa','dosen'])
            ->where('mitra_id',$mitra->id)->orderByDesc('created_at')->get();

        $stats = [
            'total'       => $pengajuan->count(),
            'review'      => $pengajuan->where('status','review_mitra')->count(),
            'aktif'       => $pengajuan->where('status','berjalan')->count(),
            'selesai'     => $pengajuan->where('status','selesai')->count(),
            'sisa_kuota'  => $mitra->sisa_kuota,
            'kuota_magang'=> $mitra->kuota_magang,
        ];

        $mahasiswaAktif = $pengajuan->whereIn('status',['berjalan','diterima_mitra']);
        return view('mitra.dashboard.index', compact('mitra','stats','mahasiswaAktif','pengajuan'));
    }

    /** Tampilkan form edit quota mitra */
    public function editKuota()
    {
        $mitra = auth()->user()->mitra;
        return view('mitra.dashboard.edit-kuota', compact('mitra'));
    }

    /** Update quota mitra */
    public function updateKuota(Request $request)
    {
        $mitra = auth()->user()->mitra;
        
        $request->validate([
            'kuota_magang' => 'required|integer|min:0|max:100',
        ], [
            'kuota_magang.required' => 'Kuota magang wajib diisi.',
            'kuota_magang.integer'  => 'Kuota magang harus berupa angka.',
            'kuota_magang.min'       => 'Kuota magang minimal 0.',
            'kuota_magang.max'       => 'Kuota magang maksimal 100.',
        ]);

        $mitra->update(['kuota_magang' => $request->kuota_magang]);

        return redirect()->route('mitra.dashboard')->with('success', 'Kuota magang berhasil diperbarui.');
    }
}