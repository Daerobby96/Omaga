<?php
// ============================================================
// app/Console/Commands/KirimReminderSelesai.php
// ============================================================
namespace App\Console\Commands;

use App\Models\PengajuanMagang;
use App\Models\Notifikasi;
use App\Mail\ReminderSelesai;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimReminderSelesai extends Command
{
    protected $signature = 'reminder:selesai {--days=7 : Jumlah hari sebelum selesai untuk reminder}';
    protected $description = 'Kirim reminder untuk mahasiswa yang akan segera selesai magang';

    public function handle()
    {
        $hari = (int) $this->option('days');
        
        $this->info("Memeriksa mahasiswa yang akan selesai dalam {$hari} hari...");

        // Ambil pengajuan yang akan selesai dalam X hari
        $tanggalTarget = now()->addDays($hari);
        
        $pengajuanAkanSelesai = PengajuanMagang::where('status', 'berjalan')
            ->whereDate('tanggal_selesai', '<=', $tanggalTarget)
            ->whereDate('tanggal_selesai', '>=', now())
            ->with(['mahasiswa', 'mitra', 'dosen'])
            ->get();

        $count = 0;

        foreach ($pengajuanAkanSelesai as $pengajuan) {
            $sisaHari = now()->diffInDays($pengajuan->tanggal_selesai, false);
            
            // Cek apakah sudah ada notifikasi untuk periode ini
            $notifExists = Notifikasi::where('user_id', $pengajuan->mahasiswa->user_id)
                ->whereDate('created_at', '>=', now()->subDays(2))
                ->where('judul', 'like', '%Menyelesaikan Magang%')
                ->exists();

            if (!$notifExists) {
                // Reminder ke mahasiswa
                Notifikasi::create([
                    'user_id' => $pengajuan->mahasiswa->user_id,
                    'judul'   => 'Pengingat: Magang Akan Selesai',
                    ' pesan'  => "Magang Anda di {$pengajuan->mitra->nama_perusahaan} akan selesai dalam {$sisaHari} hari.",
                    'tipe'    => 'info',
                    'url'     => route('mahasiswa.pengajuan.show', $pengajuan),
                ]);

                // Kirim email ke mahasiswa
                if ($pengajuan->mahasiswa->user->email) {
                    try {
                        Mail::to($pengajuan->mahasiswa->user->email)->send(
                            new ReminderSelesai($pengajuan, $sisaHari)
                        );
                    } catch (\Exception $e) {
                        $this->warn("Gagal mengirim email ke {$pengajuan->mahasiswa->user->email}");
                    }
                }

                // Reminder ke dosen pembimbing
                if ($pengajuan->dosen) {
                    Notifikasi::create([
                        'user_id' => $pengajuan->dosen->user_id,
                        'judul'   => 'Pengingat: Mahasiswa Akan Selesai Magang',
                        ' pesan'  => "Mahasiswa {$pengajuan->mahasiswa->nama_lengkap} akan selesai magang dalam {$sisaHari} hari.",
                        'tipe'    => 'info',
                        'url'     => route('dosen.bimbingan.show', $pengajuan),
                    ]);
                }

                $count++;
                $this->line("Reminder dikirim ke: {$pengajuan->mahasiswa->nama_lengkap} ({$sisaHari} hari lagi)");
            }
        }

        $this->info("Berhasil mengirim {$count} reminder selesai.");
        return 0;
    }
}
