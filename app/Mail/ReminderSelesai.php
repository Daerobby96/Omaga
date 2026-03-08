<?php
// ============================================================
// app/Mail/ReminderSelesai.php
// ============================================================
namespace App\Mail;

use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderSelesai extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PengajuanMagang $pengajuan,
        public int $sisaHari
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat: Magang Akan Selesai dalam ' . $this->sisaHari . ' hari',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder-selesai',
            with: [
                'pengajuan' => $this->pengajuan,
                'mahasiswa' => $this->pengajuan->mahasiswa,
                'mitra' => $this->pengajuan->mitra,
                'dosen' => $this->pengajuan->dosen,
                'sisaHari' => $this->sisaHari,
            ],
        );
    }
}
