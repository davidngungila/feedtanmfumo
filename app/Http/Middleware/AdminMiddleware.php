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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Block members (users with role 'user' or 'member') from accessing admin areas
        if ($user->role === 'user' || $user->hasRole('member')) {
            return redirect()->route('member.dashboard')->with('error', 'You do not have access to this area.');
        }

        // All other users (admin, staff, loan officer, etc.) can access admin areas
        // Access control for specific features is handled by role-based checks in controllers/views
        return $next($request);
    }
}
