<?php
// ============================================================
// app/Models/Dosen.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $table    = 'dosen';
    protected $fillable = [
        'user_id','nidn','nama_lengkap','program_studi','fakultas',
        'jabatan_fungsional','no_hp','kuota_bimbingan',
        'is_ketua_prodi','prodi_yang_dikelola',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function bimbingan()   { return $this->hasMany(PengajuanMagang::class, 'dosen_id'); }
    public function penilaian()   { return $this->hasManyThrough(Penilaian::class, PengajuanMagang::class, 'dosen_id', 'pengajuan_id'); }

    public function bimbinganAktif()
    {
        return $this->bimbingan()->whereIn('status',['berjalan','diterima_mitra']);
    }

    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota_bimbingan - $this->bimbinganAktif()->count());
    }
}