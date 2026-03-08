<?php
// ============================================================
// app/Mail/ReminderLogbook.php
// ============================================================
namespace App\Mail;

use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderLogbook extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PengajuanMagang $pengajuan,
        public int $hariTanpaLogbook
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder: Segera Isi Logbook Magang',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder-logbook',
            with: [
                'pengajuan' => $this->pengajuan,
                'mahasiswa' => $this->pengajuan->mahasiswa,
                'mitra' => $this->pengajuan->mitra,
                'hariTanpaLogbook' => $this->hariTanpaLogbook,
            ],
        );
    }
}
