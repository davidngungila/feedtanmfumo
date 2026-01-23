<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            // Members only go to member dashboard
            if ($user->isMember() || ($user->role === 'user' && !$user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant', 'admin']))) {
                return redirect()->intended('/member/dashboard');
            }

            // All officers and admins go to admin dashboard
            // Check if user has officer roles - redirect to role-specific dashboard
            if ($user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
                return redirect()->intended('/admin/role-dashboard');
            }

            // Default admin dashboard
            return redirect()->intended('/admin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
