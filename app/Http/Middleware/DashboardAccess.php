<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // login admin (Auth)
        if (Auth::check()) {
            return $next($request);
        }

        // login karyawan (session manual)
        if (session('login') === true && session('role') === 'karyawan') {
            return $next($request);
        }

        return redirect('/dashboard/login');
    }

}
