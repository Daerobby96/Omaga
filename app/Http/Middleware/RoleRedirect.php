<?php
// ============================================================
// app/Http/Middleware/RoleRedirect.php
// Redirect user ke dashboard sesuai role setelah login
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleRedirect
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user  = auth()->user();
            $route = $user->getDashboardRoute();
            // Jika user mencoba akses halaman yang bukan miliknya
            if ($request->is('/') || $request->is('home')) {
                return redirect()->route($route);
            }
        }
        return $next($request);
    }
}