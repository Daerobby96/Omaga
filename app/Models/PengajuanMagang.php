<?php
// ============================================================
// app/Models/PengajuanMagang.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table    = 'pengajuan_magang';
    protected $fillable = [
        'kode_pengajuan','mahasiswa_id','mitra_id','dosen_id',
        'tanggal_mulai','tanggal_selesai','bidang_kerja','deskripsi_pekerjaan',
        'surat_pengantar','proposal','nomor_surat','status',
        'catatan_koordinator','catatan_mitra',
        'disetujui_koordinator_at','diterima_mitra_at','disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_mulai'              => 'date',
        'tanggal_selesai'            => 'date',
        'disetujui_koordinator_at'   => 'datetime',
        'diterima_mitra_at'          => 'datetime',
    ];

    // Status definitions
    const STATUS_LABELS = [
        'draft'                  => ['label'=>'Draft',                'class'=>'bg-secondary-subtle text-secondary'],
        'diajukan'               => ['label'=>'Diajukan',             'class'=>'bg-info-subtle text-info'],
        'review_koordinator'     => ['label'=>'Review Koordinator',   'class'=>'bg-warning-subtle text-warning'],
        'disetujui_koordinator'  => ['label'=>'Disetujui Koordinator','class'=>'bg-primary-subtle text-primary'],
        'ditolak_koordinator'    => ['label'=>'Ditolak Koordinator',  'class'=>'bg-danger-subtle text-danger'],
        'review_mitra'           => ['label'=>'Review Mitra',         'class'=>'bg-warning-subtle text-warning'],
        'diterima_mitra'         => ['label'=>'Diterima Mitra',       'class'=>'bg-success-subtle text-success'],
        'ditolak_mitra'          => ['label'=>'Ditolak Mitra',        'class'=>'bg-danger-subtle text-danger'],
        'berjalan'               => ['label'=>'Sedang Berjalan',      'class'=>'bg-success-subtle text-success'],
        'selesai'                => ['label'=>'Selesai',              'class'=>'bg-secondary-subtle text-secondary'],
        'dibatalkan'             => ['label'=>'Dibatalkan',           'class'=>'bg-danger-subtle text-danger'],
    ];

    // Relations
    public function mahasiswa()   { return $this->belongsTo(Mahasiswa::class); }
    public function mitra()       { return $this->belongsTo(Mitra::class); }
    public function dosen()       { return $this->belongsTo(Dosen::class); }
    public function logbook()     { return $this->hasMany(Logbook::class, 'pengajuan_id'); }
    public function penilaian()   { return $this->hasOne(Penilaian::class, 'pengajuan_id'); }
    public function sertifikat()  { return $this->hasOne(Sertifikat::class, 'pengajuan_id'); }
    public function disetujuiOleh() { return $this->belongsTo(User::class, 'disetujui_oleh'); }

    // Attributes
    public function getStatusBadgeAttribute(): array
    {
        return self::STATUS_LABELS[$this->status]
            ?? ['label'=>ucfirst($this->status),'class'=>'bg-secondary-subtle text-secondary'];
    }

    public function getDurasiAttribute(): string
    {
        return $this->tanggal_mulai->format('d M Y').' – '.$this->tanggal_selesai->format('d M Y');
    }

    public function getProgressAttribute(): int
    {
        if ($this->status === 'selesai')   return 100;
        if ($this->status !== 'berjalan')  return 0;
        $total = $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
        $lewat = $this->tanggal_mulai->diffInDays(now());
        return $total > 0 ? min(100, (int) round(($lewat / $total) * 100)) : 0;
    }

    public function getSisaHariAttribute(): int
    {
        return max(0, now()->diffInDays($this->tanggal_selesai, false));
    }

    // Scopes
    public function scopeBerjalan($q)  { return $q->where('status','berjalan'); }
    public function scopeSelesai($q)   { return $q->where('status','selesai'); }
    public function scopePending($q)   { return $q->whereIn('status',['diajukan','review_koordinator','review_mitra']); }

    // Boot: auto-generate kode pengajuan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_pengajuan)) {
                $model->kode_pengajuan = 'MGG-'.date('Y').'-'.str_pad(
                    (static::whereYear('created_at', date('Y'))->count() + 1), 4, '0', STR_PAD_LEFT
                );
            }
        });
    }
}