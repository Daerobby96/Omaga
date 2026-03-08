<?php
// ============================================================
// app/Models/Diskusi.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diskusi extends Model
{
    use HasFactory;

    protected $table = 'diskusi';
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'parent_id',
        'isi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Diskusi::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Diskusi::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Scope
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForPengajuan($query, $pengajuanId)
    {
        return $query->where('pengajuan_id', $pengajuanId)->root()->with(['user', 'replies.user']);
    }
}
