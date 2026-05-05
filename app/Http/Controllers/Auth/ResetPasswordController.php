<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Display the password reset form for a given token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token = null)
    {
        // Validate and sanitize token
        if ($token === null || $token === '') {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired password reset link.');
        }
        
        // Check if token contains invalid characters
        if (strpos($token, "'") !== false) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid password reset link format.');
        }
        
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => request('email', ''),
        ]);
    }
}
