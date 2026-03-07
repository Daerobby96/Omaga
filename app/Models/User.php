<?php
// ============================================================
// app/Models/User.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name','email','password','foto','telepon','is_active'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['password'=>'hashed','is_active'=>'boolean'];

    public function mahasiswa()   { return $this->hasOne(Mahasiswa::class); }
    public function dosen()       { return $this->hasOne(Dosen::class); }
    public function mitra()       { return $this->hasOne(Mitra::class); }
    public function notifikasi()  { return $this->hasMany(Notifikasi::class); }

    public function getAvatarInitialsAttribute(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(count($parts) >= 2 ? $parts[0][0].$parts[1][0] : substr($this->name,0,2));
    }
    public function getNotifBelumDibacaAttribute(): int
    {
        return $this->notifikasi()->where('dibaca', false)->count();
    }

    // Role helpers
    public function isAdmin()      { return $this->hasRole('admin'); }
    public function isDosen()      { return $this->hasRole('dosen') || $this->hasRole('ketua_prodi'); }
    public function isMahasiswa()  { return $this->hasRole('mahasiswa'); }
    public function isMitra()      { return $this->hasRole('mitra'); }
    public function isKetuaProdi() { return $this->hasRole('ketua_prodi') || ($this->dosen && $this->dosen->is_ketua_prodi); }
    public function getDashboardRoute(): string
    {
        return match(true) {
            $this->isAdmin()     => 'admin.dashboard',
            $this->isKetuaProdi()=> 'ketua_prodi.dashboard',
            $this->isDosen()     => 'dosen.dashboard',
            $this->isMahasiswa() => 'mahasiswa.dashboard',
            $this->isMitra()     => 'mitra.dashboard',
            default              => 'home',
        };
    }
}