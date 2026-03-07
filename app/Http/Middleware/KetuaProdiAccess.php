<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KetuaProdiAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Cek jika user punya role ketua_prodi
        if ($user->hasRole('ketua_prodi')) {
            return $next($request);
        }
        
        // Cek jika user adalah dosen dengan is_ketua_prodi = true
        if ($user->dosen && $user->dosen->is_ketua_prodi == true) {
            return $next($request);
        }
        
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
