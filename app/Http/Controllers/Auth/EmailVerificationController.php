<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to verify your email.');
        }
        
        return $user->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard'))
                    : view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $user = \App\Models\User::find($request->route('id'));
        
        if (!$user || !hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            // Log in the user and redirect to dashboard
            auth()->login($user);
            return redirect()->route('dashboard')
                ->with('success', 'Your email is already verified!');
        }

        if ($user->markEmailAsVerified()) {
            // Log in the user after successful verification
            auth()->login($user);
            session([
                'verified' => true,
                'success' => 'Your email has been successfully verified!'
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent! Please check your email.');
    }
}
