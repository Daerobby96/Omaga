<?php
// ============================================================
// app/Mail/SertifikatSiap.php
// ============================================================
namespace App\Mail;

use App\Models\Sertifikat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SertifikatSiap extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Sertifikat $sertifikat) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sertifikat Magang Siap Diunduh - ' . $this->sertifikat->nomor_sertifikat,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sertifikat-siap',
            with: [
                'sertifikat' => $this->sertifikat,
                'pengajuan' => $this->sertifikat->pengajuan,
                'mahasiswa' => $this->sertifikat->mahasiswa,
            ],
        );
    }
}
