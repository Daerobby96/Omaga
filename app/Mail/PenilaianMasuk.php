<?php
// ============================================================
// app/Mail/PenilaianMasuk.php
// ============================================================
namespace App\Mail;

use App\Models\PengajuanMagang;
use App\Models\Penilaian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenilaianMasuk extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PengajuanMagang $pengajuan,
        public Penilaian $penilaian,
        public string $jenis // 'dosen' atau 'mitra'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->jenis === 'dosen' 
            ? 'Penilaian Dosen Pembimbing - ' . $this->pengajuan->kode_pengajuan
            : 'Penilaian Mitra - ' . $this->pengajuan->kode_pengajuan;
            
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penilaian-masuk',
            with: [
                'pengajuan' => $this->pengajuan,
                'penilaian' => $this->penilaian,
                'mahasiswa' => $this->pengajuan->mahasiswa,
                'jenis' => $this->jenis,
            ],
        );
    }
}
