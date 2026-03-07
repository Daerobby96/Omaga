<?php
// ============================================================
// app/Models/Notifikasi.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table    = 'notifikasi';
    protected $fillable = ['user_id','judul','pesan','tipe','url','dibaca','dibaca_at'];
    protected $casts    = ['dibaca'=>'boolean','dibaca_at'=>'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public static function kirim(int $userId, string $judul, string $pesan, string $tipe = 'info', ?string $url = null): self
    {
        return static::create(compact('userId','judul','pesan','tipe','url') + ['user_id'=>$userId]);
    }
}