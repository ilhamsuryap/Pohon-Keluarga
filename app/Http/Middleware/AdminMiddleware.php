<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login → redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Jika bukan admin
        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied. Admin privileges required.'], 403);
            }

            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
    