<?php

// ============================================================
// app/Models/Sertifikat.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    protected $fillable = [
        'pengajuan_id','mahasiswa_id','nomor_sertifikat',
        'file_sertifikat','diterbitkan_at','diterbitkan_oleh',
    ];
    protected $casts = ['diterbitkan_at'=>'datetime'];

    public function pengajuan() { return $this->belongsTo(PengajuanMagang::class); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    public function diterbitkanOleh() { return $this->belongsTo(User::class,'diterbitkan_oleh'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->nomor_sertifikat)) {
                $model->nomor_sertifikat = 'SERT/'.date('Y').'/'.str_pad(
                    (static::whereYear('created_at',date('Y'))->count()+1), 4,'0',STR_PAD_LEFT
                );
            }
        });
    }
}
