<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
// 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $rol): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::check() && Auth::user()->rol === $rol) {
            return $next($request);
        }

        abort(403, 'Acceso denegado');
    }
}
