<?php

// ============================================================
// app/Http/Controllers/Mitra/DashboardController.php
// ============================================================
namespace App\Http\Controllers\Mitra;
use App\Http\Controllers\Controller;

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
        ];

        $mahasiswaAktif = $pengajuan->whereIn('status',['berjalan','diterima_mitra']);
        return view('mitra.dashboard.index', compact('mitra','stats','mahasiswaAktif','pengajuan'));
    }
}