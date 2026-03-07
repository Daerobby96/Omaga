<?php
// ============================================================
// app/Mail/PengajuanDiterima.php
// ============================================================
namespace App\Mail;

use App\Models\PengajuanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanDiterima extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PengajuanMagang $pengajuan) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Magang Diterima - ' . $this->pengajuan->kode_pengajuan,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengajuan-diterima',
            with: [
                'pengajuan' => $this->pengajuan,
                'mahasiswa' => $this->pengajuan->mahasiswa,
                'mitra' => $this->pengajuan->mitra,
            ],
        );
    }
}
