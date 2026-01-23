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
        if ($user->isMember() || ($user->role === 'user' && !$user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant', 'admin']))) {
            return redirect()->route('member.dashboard')->with('error', 'You do not have access to this area.');
        }

        // All officers and admins can access admin areas
        // Officers: loan_officer, deposit_officer, investment_officer, chairperson, secretary, accountant
        // Access control for specific features is handled by role-based checks in controllers/views
        return $next($request);
    }
}
