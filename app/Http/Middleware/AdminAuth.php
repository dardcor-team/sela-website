<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('web')->user();

        if ($user->role !== 'super_admin') {
            Auth::guard('web')->logout();
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
        }

        return $next($request);
    }
}
