<?php
// ============================================================
// app/Console/Commands/KirimReminderLogbook.php
// ============================================================
namespace App\Console\Commands;

use App\Models\PengajuanMagang;
use App\Models\Logbook;
use App\Models\Notifikasi;
use App\Mail\ReminderLogbook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimReminderLogbook extends Command
{
    protected $signature = 'reminder:logbook {--days=3 : Jumlah hari tanpa logbook sebelum reminder}';
    protected $description = 'Kirim reminder untuk mahasiswa yang belum mengisi logbook';

    public function handle()
    {
        $hari = (int) $this->option('days');
        
        $this->info("Memeriksa mahasiswa yang belum mengisi logbook selama {$hari} hari...");

        // Ambil semua pengajuan yang sedang berjalan
        $pengajuanBerjalan = PengajuanMagang::where('status', 'berjalan')
            ->with(['mahasiswa', 'mitra'])
            ->get();

        $count = 0;

        foreach ($pengajuanBerjalan as $pengajuan) {
            // Cek logbook terakhir
            $logbookTerakhir = Logbook::where('pengajuan_id', $pengajuan->id)
                ->orderByDesc('tanggal')
                ->first();

            $hariTanpaLogbook = $logbookTerakhir 
                ? $logbookTerakhir->tanggal->diffInDays(now())
                : $pengajuan->tanggal_mulai->diffInDays(now());

            // Jika lebih dari X hari tanpa logbook, kirim reminder
            if ($hariTanpaLogbook >= $hari) {
                // Cek apakah sudah ada notifikasi hari ini
                $notifHariIni = Notifikasi::where('user_id', $pengajuan->mahasiswa->user_id)
                    ->whereDate('created_at', today())
                    ->where('judul', 'like', '%Reminder Logbook%')
                    ->exists();

                if (!$notifHariIni) {
                    Notifikasi::create([
                        'user_id' => $pengajuan->mahasiswa->user_id,
                        'judul'   => 'Reminder: Isi Logbook Magang',
                        ' pesan'  => "Anda belum mengisi logbook selama {$hariTanpaLogbook} hari. Segera isi logbook harian Anda.",
                        'tipe'    => 'warning',
                        'url'     => route('mahasiswa.logbook.create'),
                    ]);

                    // Kirim email jika user memiliki email
                    if ($pengajuan->mahasiswa->user->email) {
                        try {
                            Mail::to($pengajuan->mahasiswa->user->email)->send(
                                new ReminderLogbook($pengajuan, $hariTanpaLogbook)
                            );
                        } catch (\Exception $e) {
                            $this->warn("Gagal mengirim email ke {$pengajuan->mahasiswa->user->email}");
                        }
                    }

                    $count++;
                    $this->line("Reminder dikirim ke: {$pengajuan->mahasiswa->nama_lengkap}");
                }
            }
        }

        $this->info("Berhasil mengirim {$count} reminder logbook.");
        return 0;
    }
}
