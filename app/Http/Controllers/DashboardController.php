<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard (redirects based on role)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect members to member dashboard
        if ($user->isMember() || ($user->role === 'user' && !$user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant', 'admin']))) {
            return redirect()->route('member.dashboard');
        }
        
        // Redirect officers to role-specific dashboard
        if ($user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
            return redirect()->route('admin.role-dashboard');
        }
        
        // Redirect all other users (admin, etc.) to admin dashboard
        return redirect()->route('admin.dashboard');
    }
}
