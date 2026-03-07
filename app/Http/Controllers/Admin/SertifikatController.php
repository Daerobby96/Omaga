<?php

// ============================================================
// app/Http/Controllers/Admin/SertifikatController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PengajuanMagang, Sertifikat, Notifikasi};
use App\Mail\SertifikatSiap;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SertifikatController extends Controller
{
    public function index()
    {
        $sertifikat = Sertifikat::with(['mahasiswa','pengajuan.mitra'])
            ->orderByDesc('created_at')->paginate(20);
        return view('admin.sertifikat.index', compact('sertifikat'));
    }

    public function settings()
    {
        $settings = [
            'logo' => config('sertifikat.logo', null),
            'primary_color' => config('sertifikat.primary_color', '#1a3a6b'),
            'accent_color' => config('sertifikat.accent_color', '#c9a84c'),
            'university_name' => config('sertifikat.university_name', 'UNIVERSITAS NEGERI CONTOH'),
            'faculty' => config('sertifikat.faculty', 'Fakultas Teknik'),
            'study_program' => config('sertifikat.study_program', 'Program Studi Teknik Informatika'),
            'address' => config('sertifikat.address', 'Jl. Kampus Raya No. 1, Kota Universitas'),
            'phone' => config('sertifikat.phone', '(021) 1234567'),
            'nama_koordinator' => config('sertifikat.nama_koordinator', ''),
        ];
        
        return view('admin.sertifikat.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'ttd_koordinator' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'primary_color' => 'nullable|max:7',
            'accent_color' => 'nullable|max:7',
            'university_name' => 'nullable|max:255',
            'faculty' => 'nullable|max:255',
            'study_program' => 'nullable|max:255',
            'address' => 'nullable|max:500',
            'phone' => 'nullable|max:50',
            'nama_koordinator' => 'nullable|max:255',
        ]);

        // Handle logo upload
        $logo = null;
        if ($request->hasFile('logo')) {
            // Delete old logo
            $oldLogo = config('sertifikat.logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            // Upload new logo
            $logo = $request->file('logo')->store('sertifikat/logo', 'public');
        }

        // Handle signature upload
        $ttdKoordinator = null;
        if ($request->hasFile('ttd_koordinator')) {
            $oldTtd = config('sertifikat.ttd_koordinator');
            if ($oldTtd && Storage::disk('public')->exists($oldTtd)) {
                Storage::disk('public')->delete($oldTtd);
            }
            $ttdKoordinator = $request->file('ttd_koordinator')->store('sertifikat/ttd', 'public');
        }

        // Save settings to config file
        $configContent = file_exists(config_path('sertifikat.php')) 
            ? include config_path('sertifikat.php') 
            : [];

        $newConfig = array_merge($configContent, [
            'primary_color' => $request->primary_color ?? '#1a3a6b',
            'accent_color' => $request->accent_color ?? '#c9a84c',
            'university_name' => $request->university_name ?? 'UNIVERSITAS NEGERI CONTOH',
            'faculty' => $request->faculty ?? 'Fakultas Teknik',
            'study_program' => $request->study_program ?? 'Program Studi Teknik Informatika',
            'address' => $request->address ?? 'Jl. Kampus Raya No. 1, Kota Universitas',
            'phone' => $request->phone ?? '(021) 1234567',
            'nama_koordinator' => $request->nama_koordinator ?? '',
        ]);

        if ($logo) {
            $newConfig['logo'] = $logo;
        }
        if ($ttdKoordinator) {
            $newConfig['ttd_koordinator'] = $ttdKoordinator;
        }

        // Write config file
        $configPath = config_path('sertifikat.php');
        $configContent = "<?php\n\nreturn [\n";
        foreach ($newConfig as $key => $value) {
            $configContent .= "    '$key' => " . var_export($value, true) . ",\n";
        }
        $configContent .= "];\n";

        File::put($configPath, $configContent);

        // Clear config cache
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return back()->with('success', 'Pengaturan sertifikat berhasil diperbarui.');
    }

    public function generate(PengajuanMagang $pengajuan)
    {
        abort_unless($pengajuan->status === 'selesai', 403, 'Magang belum selesai.');
        abort_unless($pengajuan->penilaian?->lulus, 403, 'Mahasiswa belum lulus penilaian.');

        // Reload to get latest data
        $pengajuan->refresh();
        
        // Create or get existing certificate
        $sertifikat = Sertifikat::firstOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            [
                'mahasiswa_id'    => $pengajuan->mahasiswa_id,
                'diterbitkan_at'  => now(),
                'diterbitkan_oleh'=> auth()->id(),
            ]
        );

        // If already has file, skip generation
        if ($sertifikat->file_sertifikat && Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            return back()->with('info', "Sertifikat {$sertifikat->nomor_sertifikat} sudah ada.");
        }

        // Ensure we have nomor_sertifikat
        if (empty($sertifikat->nomor_sertifikat)) {
            $sertifikat->nomor_sertifikat = 'SERT/'.date('Y').'/'.str_pad(
                (Sertifikat::whereYear('created_at',date('Y'))->count()+1), 4,'0',STR_PAD_LEFT
            );
            $sertifikat->save();
        }

        // Generate PDF with settings
        $settings = [
            'logo' => config('sertifikat.logo'),
            'ttd_koordinator' => config('sertifikat.ttd_koordinator'),
            'primary_color' => config('sertifikat.primary_color', '#1a3a6b'),
            'accent_color' => config('sertifikat.accent_color', '#c9a84c'),
            'university_name' => config('sertifikat.university_name', 'UNIVERSITAS NEGERI CONTOH'),
            'faculty' => config('sertifikat.faculty', 'Fakultas Teknik'),
            'study_program' => config('sertifikat.study_program', 'Program Studi Teknik Informatika'),
            'address' => config('sertifikat.address', 'Jl. Kampus Raya No. 1, Kota Universitas'),
            'phone' => config('sertifikat.phone', '(021) 1234567'),
            'nama_koordinator' => config('sertifikat.nama_koordinator', ''),
        ];
        
        $pdf = Pdf::setPaper('a4','landscape')
                    ->setOption('isRemoteEnabled', true)
                    ->setOption('isPhpEnabled', true)
                    ->setOption('defaultFont', 'DejaVu Sans')
                    ->loadView('admin.sertifikat.template', compact('sertifikat', 'pengajuan', 'settings'));
        $path = "sertifikat/{$sertifikat->nomor_sertifikat}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        $sertifikat->update(['file_sertifikat'=>$path]);

        Notifikasi::create([
            'user_id' => $pengajuan->mahasiswa->user_id,
            'judul'   => 'Sertifikat Magang Tersedia',
            'pesan'   => "Sertifikat magang Anda telah diterbitkan. Silakan unduh.",
            'tipe'    => 'success',
            'url'     => route('mahasiswa.sertifikat.download',$sertifikat),
        ]);

        // Kirim email notifikasi ke mahasiswa
        Mail::to($pengajuan->mahasiswa->user->email)->send(new SertifikatSiap($sertifikat));

        return back()->with('success',"Sertifikat {$sertifikat->nomor_sertifikat} berhasil diterbitkan.");
    }

    public function download(Sertifikat $sertifikat)
    {
        if (empty($sertifikat->file_sertifikat)) {
            abort(404, 'File sertifikat belum tersedia.');
        }
        if (!Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            abort(404, 'File sertifikat tidak ditemukan.');
        }
        return Storage::disk('public')->download($sertifikat->file_sertifikat, "Sertifikat_" . str_replace(['/', '\\'], '-', $sertifikat->nomor_sertifikat) . ".pdf");
    }

    public function downloadImage(Sertifikat $sertifikat)
    {
        $pengajuan = $sertifikat->pengajuan;
        $settings = [
            'primary_color' => config('sertifikat.primary_color', '#1a3a6b'),
            'accent_color' => config('sertifikat.accent_color', '#c9a84c'),
            'university_name' => config('sertifikat.university_name', 'UNIVERSITAS NEGERI CONTOH'),
            'faculty' => config('sertifikat.faculty', 'Fakultas Teknik'),
            'study_program' => config('sertifikat.study_program', 'Program Studi Teknik Informatika'),
            'address' => config('sertifikat.address', 'Jl. Kampus Raya No. 1, Kota Universitas'),
            'phone' => config('sertifikat.phone', '(021) 1234567'),
        ];
        
        // Generate PDF
        $pdf = Pdf::setPaper('a4','landscape')
                    ->setOption('isRemoteEnabled', true)
                    ->setOption('isPhpEnabled', true)
                    ->setOption('defaultFont', 'DejaVu Sans')
                    ->loadView('admin.sertifikat.template', compact('sertifikat', 'pengajuan', 'settings'));
        
        // Try to output as PNG
        try {
            $image = $pdf->output();
            $filename = "Sertifikat_" . str_replace(['/', '\\'], '-', $sertifikat->nomor_sertifikat) . ".png";
            
            return response($image, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gambar tidak tersedia. extensions GD/Imagick mungkin belum terinstal.');
        }
    }

    public function view(Sertifikat $sertifikat)
    {
        $pengajuan = $sertifikat->pengajuan;
        $settings = [
            'logo' => config('sertifikat.logo'),
            'ttd_koordinator' => config('sertifikat.ttd_koordinator'),
            'primary_color' => config('sertifikat.primary_color', '#1a3a6b'),
            'accent_color' => config('sertifikat.accent_color', '#c9a84c'),
            'university_name' => config('sertifikat.university_name', 'UNIVERSITAS NEGERI CONTOH'),
            'faculty' => config('sertifikat.faculty', 'Fakultas Teknik'),
            'study_program' => config('sertifikat.study_program', 'Program Studi Teknik Informatika'),
            'address' => config('sertifikat.address', 'Jl. Kampus Raya No. 1, Kota Universitas'),
            'phone' => config('sertifikat.phone', '(021) 1234567'),
            'nama_koordinator' => config('sertifikat.nama_koordinator', ''),
        ];
        
        return view('admin.sertifikat.template', compact('sertifikat', 'pengajuan', 'settings'));
    }
}
