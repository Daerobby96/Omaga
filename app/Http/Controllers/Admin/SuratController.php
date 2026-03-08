<?php
// ============================================================
// app/Http/Controllers/Admin/SuratController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    /**
     * Preview Surat Pengantar Magang
     */
    public function previewPengantar(PengajuanMagang $pengajuan)
    {
        abort_unless(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']), 403, 'Pengajuan belum disetujui.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id),
        ];

        $pdf = Pdf::loadView('admin.surat.pengantar', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream("Surat_Pengantar_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Preview Surat Pengantar Magang (untuk Mitra)
     */
    public function previewPengantarMitra(PengajuanMagang $pengajuan)
    {
        // Verifikasi bahwa yang akses adalah mitra
        $mitra = auth()->user()->mitra;
        abort_if(!$mitra, 403, 'Akses ditolak. Anda bukan mitra.');
        abort_unless($pengajuan->mitra_id === $mitra->id, 403, 'Anda tidak memiliki akses ke pengajuan ini.');
        abort_unless(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']), 403, 'Pengajuan belum disetujui.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id),
        ];

        $pdf = Pdf::loadView('admin.surat.pengantar', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream("Surat_Pengantar_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Preview Surat Pengantar Magang (untuk Dosen Pembimbing)
     */
    public function previewPengantarDosen(PengajuanMagang $pengajuan)
    {
        // Verifikasi bahwa yang akses adalah dosen
        $dosen = auth()->user()->dosen;
        abort_if(!$dosen, 403, 'Akses ditolak. Anda bukan dosen.');
        abort_unless($pengajuan->dosen_id === $dosen->id, 403, 'Anda tidak memiliki akses ke pengajuan ini.');
        abort_unless(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']), 403, 'Pengajuan belum disetujui.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id),
        ];

        $pdf = Pdf::loadView('admin.surat.pengantar', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream("Surat_Pengantar_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Generate Surat Pengantar Magang (untuk mahasiswa bawa ke mitra)
     */
    public function pengantar(PengajuanMagang $pengajuan)
    {
        abort_unless(in_array($pengajuan->status, ['disetujui_koordinator', 'diterima_mitra', 'berjalan']), 403, 'Pengajuan belum disetujui.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id),
        ];

        $pdf = Pdf::loadView('admin.surat.pengantar', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->download("Surat_Pengantar_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Preview Surat Pengajuan ke Mitra
     */
    public function previewPengajuan(PengajuanMagang $pengajuan)
    {
        abort_unless(in_array($pengajuan->status, ['diajukan', 'review_koordinator', 'disetujui_koordinator', 'review_mitra']), 403, 'Status pengajuan tidak sesuai.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id, 'PENGAJUAN'),
        ];

        $pdf = Pdf::loadView('admin.surat.pengajuan-mitra', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream("Surat_Pengajuan_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Generate Surat Pengajuan ke Mitra (surat resmi dari universitas)
     */
    public function pengajuan(PengajuanMagang $pengajuan)
    {
        abort_unless(in_array($pengajuan->status, ['diajukan', 'review_koordinator', 'disetujui_koordinator', 'review_mitra']), 403, 'Status pengajuan tidak sesuai.');
        
        $pengajuan->load(['mahasiswa', 'mitra', 'dosen']);
        
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'mitra' => $pengajuan->mitra,
            'dosen' => $pengajuan->dosen,
            'program_studi' => $pengajuan->mahasiswa->program_studi,
            'tanggal' => now()->format('d F Y'),
            'nomor_surat' => $pengajuan->nomor_surat ?? $this->generateNomorSurat($pengajuan->id, 'PENGAJUAN'),
        ];

        $pdf = Pdf::loadView('admin.surat.pengajuan-mitra', $data)
            ->setPaper('a4', 'portrait');
            
        return $pdf->download("Surat_Pengajuan_{$pengajuan->kode_pengajuan}.pdf");
    }

    /**
     * Generate nomor surat otomatis
     */
    private function generateNomorSurat(int $pengajuanId, string $prefix = 'PENGANTAR'): string
    {
        $tahun = date('Y');
        $nomor = str_pad($pengajuanId, 4, '0', STR_PAD_LEFT);
        return "{$prefix}/{$nomor}/XII/{$tahun}";
    }
}
