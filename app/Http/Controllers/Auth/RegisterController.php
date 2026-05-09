<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function __construct(
        protected EmailNotificationService $emailService,
        protected SmsNotificationService $smsService
    ) {}

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted',
        ]);

        // Extract name from email before @ symbol if no name provided
        $emailParts = explode('@', $request->email);
        $name = ucfirst($emailParts[0]);

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'membership_status' => 'pending',
            'email_verified_at' => null, // Ensure email is not verified initially
        ]);

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Redirect to email verification notice
        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}
