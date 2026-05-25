<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = Cache::get('app_maintenance', false);

        if ($isMaintenance) {
            // Allow admin routes, maintenance route, and super_admin users
            if ($request->is('admin*') || $request->is('maintenance') || $request->is('up')) {
                return $next($request);
            }

            // If user is logged in as super_admin under web or via token, allow them
            $user = Auth::guard('web')->user() ?? $request->user();
            if ($user && $user->role === 'super_admin') {
                return $next($request);
            }

            // Return 503 for API or JSON requests
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Service Unavailable',
                    'status' => 'maintenance',
                    'description' => 'Aplikasi sedang dalam pemeliharaan. Silakan coba beberapa saat lagi.'
                ], 503);
            }

            // Redirect web requests to /maintenance
            return redirect('/maintenance');
        }

        // If not in maintenance and user visits /maintenance, redirect to home
        if ($request->is('maintenance')) {
            return redirect('/');
        }

        return $next($request);
    }
}
