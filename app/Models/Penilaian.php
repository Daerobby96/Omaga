<?php

// ============================================================
// app/Models/Penilaian.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $fillable = [
        'pengajuan_id','mahasiswa_id',
        'nilai_pembimbingan','nilai_laporan','nilai_seminar','catatan_dosen','dinilai_dosen_at',
        'nilai_kedisiplinan','nilai_kemampuan_teknis','nilai_komunikasi',
        'nilai_inisiatif','nilai_kerjasama','catatan_mitra','dinilai_mitra_at',
        'nilai_akhir','grade','lulus',
    ];
    protected $casts = ['dinilai_dosen_at'=>'datetime','dinilai_mitra_at'=>'datetime','lulus'=>'boolean'];

    public function pengajuan() { return $this->belongsTo(PengajuanMagang::class); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }

    // Bobot: Dosen 60%, Mitra 40%
    public function hitungNilaiAkhir(): float
    {
        $nilaiDosen = ($this->nilai_pembimbingan + $this->nilai_laporan + $this->nilai_seminar) / 3;
        $nilaiMitra = ($this->nilai_kedisiplinan + $this->nilai_kemampuan_teknis
                     + $this->nilai_komunikasi + $this->nilai_inisiatif + $this->nilai_kerjasama) / 5;
        return round(($nilaiDosen * 0.6) + ($nilaiMitra * 0.4), 2);
    }

    public function hitungGrade(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 75 => 'B',
            $nilai >= 65 => 'C',
            $nilai >= 55 => 'D',
            default      => 'E',
        };
    }

    public function dosenSudahNilai(): bool
    {
        return !is_null($this->dinilai_dosen_at);
    }

    public function mitraSudahNilai(): bool
    {
        return !is_null($this->dinilai_mitra_at);
    }
}
