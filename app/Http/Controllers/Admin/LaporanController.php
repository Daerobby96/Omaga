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
use App\Models\Prodi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $prodi = $request->prodi;
        $semester = $request->semester;

        // Get prodi list for filter
        $prodiList = Prodi::orderBy('nama_prodi')->get();

        // Build base query for filtering
        $query = PengajuanMagang::whereYear('created_at', $tahun);

        if ($prodi) {
            $query->whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where('program_studi', $prodi);
            });
        }

        if ($semester) {
            $query->whereHas('mahasiswa', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $data  = [
            'total_pengajuan' => (clone $query)->count(),
            'berjalan'        => (clone $query)->berjalan()->count(),
            'selesai'         => (clone $query)->selesai()->count(),
            'per_bulan'       => collect(range(1,12))->map(fn($m) => [
                'bulan'  => Carbon::create(null,$m)->format('M'),
                'jumlah' => (clone $query)->whereMonth('created_at',$m)->count(),
            ]),
            'per_mitra' => Mitra::withCount(['pengajuan'=>fn($q)=>$q->whereYear('created_at',$tahun)
                ->when($prodi, fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('program_studi', $prodi)))
                ->when($semester, fn($q) => $q->whereHas('mahasiswa', fn($q2) => $q2->where('semester', $semester)))
            ])
                               ->orderByDesc('pengajuan_count')->take(10)->get(),
            'per_prodi' => Mahasiswa::selectRaw('program_studi, COUNT(*) as total')
                               ->when($prodi, fn($q) => $q->where('program_studi', $prodi))
                               ->when($semester, fn($q) => $q->where('semester', $semester))
                               ->groupBy('program_studi')->orderByDesc('total')->get(),
        ];
        $tahun_list = range(date('Y'), date('Y')-5);
        $semester_list = range(1, 8); // Semester 1-8
        return view('admin.laporan.index', compact('data','tahun','tahun_list','prodiList','prodi','semester','semester_list'));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $prodi = $request->prodi;
        $semester = $request->semester;

        $query = PengajuanMagang::with(['mahasiswa','mitra','penilaian'])
            ->whereYear('created_at',$tahun)->selesai();

        if ($prodi) {
            $query->whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where('program_studi', $prodi);
            });
        }

        if ($semester) {
            $query->whereHas('mahasiswa', function($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $data = $query->get();
        $pdf   = Pdf::loadView('admin.laporan.pdf', compact('data','tahun','prodi','semester'))->setPaper('a4','landscape');
        return $pdf->download("Laporan_Magang_{$tahun}.pdf");
    }
}
