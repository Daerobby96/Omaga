<?php
// ============================================================
// app/Mail/PengajuanDitolak.php
// ============================================================
namespace App\Mail;

use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanDitolak extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PengajuanMagang $pengajuan,
        public ?string $alasan = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Magang Ditolak - ' . $this->pengajuan->kode_pengajuan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan-ditolak',
            with: [
                'pengajuan' => $this->pengajuan,
                'mahasiswa' => $this->pengajuan->mahasiswa,
                'mitra' => $this->pengajuan->mitra,
                'alasan' => $this->alasan,
            ],
        );
    }
}
