<?php

// ============================================================
// app/Http/Controllers/Admin/LaporanController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use App\Models\Mitra;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $data  = [
            'total_pengajuan' => PengajuanMagang::whereYear('created_at',$tahun)->count(),
            'berjalan'        => PengajuanMagang::whereYear('created_at',$tahun)->berjalan()->count(),
            'selesai'         => PengajuanMagang::whereYear('created_at',$tahun)->selesai()->count(),
            'per_bulan'       => collect(range(1,12))->map(fn($m) => [
                'bulan'  => Carbon::create(null,$m)->format('M'),
                'jumlah' => PengajuanMagang::whereYear('created_at',$tahun)->whereMonth('created_at',$m)->count(),
            ]),
            'per_mitra' => Mitra::withCount(['pengajuan'=>fn($q)=>$q->whereYear('created_at',$tahun)])
                               ->orderByDesc('pengajuan_count')->take(10)->get(),
            'per_prodi' => Mahasiswa::selectRaw('program_studi, COUNT(*) as total')
                               ->groupBy('program_studi')->orderByDesc('total')->get(),
        ];
        $tahun_list = range(date('Y'), date('Y')-5);
        return view('admin.laporan.index', compact('data','tahun','tahun_list'));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $data  = PengajuanMagang::with(['mahasiswa','mitra','penilaian'])
            ->whereYear('created_at',$tahun)->selesai()->get();
        $pdf   = Pdf::loadView('admin.laporan.pdf', compact('data','tahun'))->setPaper('a4','landscape');
        return $pdf->download("Laporan_Magang_{$tahun}.pdf");
    }
}
