<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('landingpage')->with('error', 'You must be logged in to access this page.');
        }

        // If no roles specified or user is admin, proceed
        if (empty($roles) || Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Check if user has one of the required roles
        foreach ($roles as $role) {
            // For executive role, also allow staff with higher permissions
            if ($role === 'executive' && in_array(Auth::user()->role, ['executive'])) {
                return $next($request);
            }

            // For staff role, also allow executives
            if ($role === 'staff' && in_array(Auth::user()->role, ['staff', 'executive'])) {
                return $next($request);
            }

            // Direct role match
            if (Auth::user()->role === $role) {
                return $next($request);
            }
        }

        // User doesn't have any of the required roles
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
