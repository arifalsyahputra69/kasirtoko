<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Akun Anda tidak aktif.'
            ]);
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'AKSES DITOLAK');
        }

        return $next($request);
    }
}
