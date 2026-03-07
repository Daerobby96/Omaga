<?php
// ============================================================
// app/Models/Mitra.php
// ============================================================
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;
    protected $table = 'mitra';
    protected $fillable = [
        'user_id','nama_perusahaan','bidang_usaha','nama_kontak','jabatan_kontak',
        'email_perusahaan','telepon','alamat','website','logo','deskripsi',
        'kuota_magang','status','catatan_admin','tanda_tangan',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function pengajuan() { return $this->hasMany(PengajuanMagang::class); }

    public function pengajuanAktif()
    {
        return $this->pengajuan()->whereIn('status',['berjalan','diterima_mitra']);
    }

    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota_magang - $this->pengajuanAktif()->count());
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'aktif'    => ['class'=>'bg-success-subtle text-success',   'label'=>'Aktif'],
            'nonaktif' => ['class'=>'bg-danger-subtle text-danger',     'label'=>'Non-Aktif'],
            'pending'  => ['class'=>'bg-warning-subtle text-warning',   'label'=>'Pending'],
            default    => ['class'=>'bg-secondary-subtle text-secondary','label'=>ucfirst($this->status)],
        };
    }
}