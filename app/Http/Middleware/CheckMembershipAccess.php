<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMembershipAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $service): Response
    {
        $user = auth()->user();

        // Admin and staff bypass membership checks
        if ($user->isAdmin() || $user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
            return $next($request);
        }

        // Check if user has approved membership
        if ($user->membership_status !== 'approved') {
            return redirect()->route('member.membership.status')
                ->with('error', 'Your membership application is pending approval. You cannot access this service yet.');
        }

        // Check if user's membership type has access to this service
        if (!$user->hasMembershipAccessTo($service)) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Your membership type does not have access to this service.');
        }

        return $next($request);
    }
}
