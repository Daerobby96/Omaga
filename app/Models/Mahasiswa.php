<?php
// ============================================================
// app/Models/Mahasiswa.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = [
        'user_id','nim','nama_lengkap','program_studi','fakultas',
        'semester','angkatan','no_hp','alamat','foto','cv','transkrip',
        'ipk','status_akademik',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function pengajuan() { return $this->hasMany(PengajuanMagang::class); }

    public function pengajuanAktif()
    {
        return $this->pengajuan()->whereIn('status',['berjalan','diterima_mitra','diterima_dosen']);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status_akademik) {
            'aktif'  => ['class'=>'bg-success-subtle text-success',   'label'=>'Aktif'],
            'cuti'   => ['class'=>'bg-warning-subtle text-warning',   'label'=>'Cuti'],
            'lulus'  => ['class'=>'bg-info-subtle text-info',         'label'=>'Lulus'],
            default  => ['class'=>'bg-secondary-subtle text-secondary','label'=>ucfirst($this->status_akademik)],
        };
    }
}
