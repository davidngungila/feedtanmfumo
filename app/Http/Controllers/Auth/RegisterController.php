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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Store plain password before hashing for notifications
        $plainPassword = $request->password;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'membership_status' => 'pending',
        ]);

        // Send welcome email with credentials
        $this->emailService->sendWelcomeEmail($user, $plainPassword);

        // Send welcome SMS with credentials
        $this->smsService->sendWelcomeSms($user, $plainPassword);

        Auth::login($user);

        // Redirect to membership application form
        return redirect()->route('member.membership.application');
    }
}
