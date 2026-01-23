<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

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
            
            // Check if user is an officer (requires OTP)
            $isOfficer = $user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant']) || $user->isAdmin();
            
            if ($isOfficer) {
                // Generate and send OTP
                $otp = OtpCode::createForUser($user, 'login', 10);
                
                // Send OTP via email
                $this->sendOtpEmail($user, $otp->code);
                
                // Store user ID in session for OTP verification
                $request->session()->put('otp_user_id', $user->id);
                Auth::logout(); // Logout temporarily until OTP is verified
                
                return redirect()->route('otp.verify')->with('success', 'An OTP code has been sent to your email. Please enter it to complete login.');
            }
            
            // Members don't need OTP - redirect directly
            if ($user->isMember() || ($user->role === 'user' && !$isOfficer)) {
                return redirect()->intended('/member/dashboard');
            }

            // Default admin dashboard (shouldn't reach here for officers, but just in case)
            return redirect()->intended('/admin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpVerification()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        return view('auth.otp-verify');
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Find valid OTP
        $otp = OtpCode::where('user_id', $user->id)
            ->where('code', $request->otp_code)
            ->where('type', 'login')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP code.'])->withInput();
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Login the user
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        $request->session()->forget('otp_user_id');

        // Redirect based on role
        if ($user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
            return redirect()->intended('/admin/role-dashboard');
        }

        return redirect()->intended('/admin/dashboard');
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request)
    {
        try {
            $userId = session('otp_user_id');
            
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            $user = User::find($userId);
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'User not found.');
            }

            // Check rate limiting - prevent too many resend requests
            $recentOtps = OtpCode::where('user_id', $user->id)
                ->where('type', 'login')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->count();
            
            if ($recentOtps >= 3) {
                return back()->with('error', 'Too many requests. Please wait a moment before requesting another OTP code.');
            }

            // Generate new OTP
            $otp = OtpCode::createForUser($user, 'login', 10);
            
            // Send OTP via email
            $emailSent = $this->sendOtpEmail($user, $otp->code);
            
            if (!$emailSent) {
                \Log::error("Failed to send resend OTP email to user ID: {$user->id}");
                return back()->with('error', 'Failed to send OTP email. Please try again or contact support.');
            }

            \Log::info("OTP resent to user ID: {$user->id}, email: {$user->email}");
            
            return back()->with('success', 'A new OTP code has been sent to your email address. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Error resending OTP: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'An error occurred while resending the OTP. Please try again.');
        }
    }

    /**
     * Send OTP code via email
     */
    protected function sendOtpEmail(User $user, string $code): bool
    {
        try {
            // Use EmailNotificationService to send OTP email
            $result = $this->emailService->sendOtpNotification($user, $code);
            
            if ($result) {
                \Log::info("OTP email sent successfully to {$user->email} for user ID {$user->id}.");
            } else {
                \Log::warning("OTP email sending returned false for user ID {$user->id}.");
            }
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Reload mail configuration from database
     */
    protected function reloadMailConfig(): void
    {
        try {
            $settings = \App\Models\Setting::getByGroup('communication');
            
            if (isset($settings['mail_mailer']) && $settings['mail_mailer']->value) {
                \Illuminate\Support\Facades\Config::set('mail.default', $settings['mail_mailer']->value);
            }
            
            if (isset($settings['mail_host']) && $settings['mail_host']->value) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host', $settings['mail_host']->value);
            }
            
            if (isset($settings['mail_port'])) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port', $settings['mail_port']->value ?? 587);
            }
            
            if (isset($settings['mail_username']) && $settings['mail_username']->value) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username', $settings['mail_username']->value);
            }
            
            if (isset($settings['mail_password']) && $settings['mail_password']->value) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password', $settings['mail_password']->value);
            }
            
            if (isset($settings['mail_encryption']) && $settings['mail_encryption']->value) {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption']->value);
            } else {
                \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption', 'tls');
            }
            
            if (isset($settings['mail_from_address']) && $settings['mail_from_address']->value) {
                \Illuminate\Support\Facades\Config::set('mail.from.address', $settings['mail_from_address']->value);
            }
            
            if (isset($settings['mail_from_name']) && $settings['mail_from_name']->value) {
                \Illuminate\Support\Facades\Config::set('mail.from.name', $settings['mail_from_name']->value);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to reload mail config: ' . $e->getMessage());
        }
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
