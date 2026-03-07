<?php

// ============================================================
// app/Models/Logbook.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table    = 'logbook';
    protected $fillable = [
        'pengajuan_id','mahasiswa_id','tanggal','jam_masuk','jam_keluar',
        'kegiatan','hasil','kendala','foto_kegiatan','status',
        'catatan_dosen','catatan_mitra','disetujui_at',
    ];
    protected $casts = ['tanggal'=>'date','disetujui_at'=>'datetime'];

    public function pengajuan()  { return $this->belongsTo(PengajuanMagang::class); }
    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }

    public function getDurasiKerjaAttribute(): ?string
    {
        if (!$this->jam_masuk || !$this->jam_keluar) return null;
        $menit = \Carbon\Carbon::parse($this->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($this->jam_keluar));
        return sprintf('%dj %dm', intdiv($menit,60), $menit % 60);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'draft'     => ['class'=>'bg-secondary-subtle text-secondary','label'=>'Draft'],
            'submitted' => ['class'=>'bg-info-subtle text-info',         'label'=>'Dikirim'],
            'disetujui' => ['class'=>'bg-success-subtle text-success',   'label'=>'Disetujui'],
            'revisi'    => ['class'=>'bg-warning-subtle text-warning',   'label'=>'Revisi'],
            default     => ['class'=>'bg-secondary-subtle text-secondary','label'=>ucfirst($this->status)],
        };
    }
}

