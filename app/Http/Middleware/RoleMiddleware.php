<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai (misal 'admin')
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request); // Silakan masuk
        }

        // Jika bukan admin, tendang keluar (Akses ditolak)
        abort(403, 'Akses Ditolak! Halaman ini khusus untuk Admin CV Widi.');
    }
}