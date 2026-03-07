<?php

// ============================================================
// app/Models/Prodi.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $fillable = ['nama_prodi', 'kode_prodi', 'fakultas', 'jenjang', 'akreditasi', 'tahun_akreditasi', 'status'];

    public function mahasiswa() { return $this->hasMany(Mahasiswa::class, 'program_studi', 'nama_prodi'); }
    public function dosen() { return $this->hasMany(Dosen::class, 'program_studi', 'nama_prodi'); }
    public function pengajuan() { return $this->hasManyThrough(PengajuanMagang::class, Mahasiswa::class, 'program_studi', 'mahasiswa_id', 'nama_prodi'); }
}
