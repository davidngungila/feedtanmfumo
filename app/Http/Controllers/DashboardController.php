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
        if ($user->role === 'user' || $user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }
        
        // Redirect all other users (admin, staff, etc.) to admin dashboard
        return redirect()->route('admin.dashboard');
    }
}
