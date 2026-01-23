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
        try {
            $request->validate([
                'otp_code' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            ], [
                'otp_code.required' => 'OTP code is required.',
                'otp_code.size' => 'OTP code must be exactly 6 digits.',
                'otp_code.regex' => 'OTP code must contain only numbers.',
            ]);

            $userId = session('otp_user_id');
            
            if (!$userId) {
                \Log::warning('OTP verification attempted without session user_id');
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            $user = User::find($userId);
            
            if (!$user) {
                \Log::warning("OTP verification attempted for non-existent user ID: {$userId}");
                return redirect()->route('login')->with('error', 'User not found.');
            }

            // Clean and normalize OTP code (remove spaces, ensure numeric)
            $otpCode = preg_replace('/[^0-9]/', '', trim($request->otp_code));
            
            if (strlen($otpCode) !== 6) {
                \Log::warning("Invalid OTP code length for user ID: {$user->id}, code length: " . strlen($otpCode));
                return back()->withErrors(['otp_code' => 'OTP code must be exactly 6 digits.'])->withInput();
            }

            // Debug: Log all recent OTPs for this user
            $recentOtps = OtpCode::where('user_id', $user->id)
                ->where('type', 'login')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['code', 'used', 'expires_at', 'created_at']);
            
            \Log::info("OTP Verification Attempt - User ID: {$user->id}, Input Code: {$otpCode}");
            \Log::info("Recent OTPs for user: " . json_encode($recentOtps->map(function($o) use ($otpCode) {
                return [
                    'code' => $o->code,
                    'used' => $o->used,
                    'expires_at' => $o->expires_at->toDateTimeString(),
                    'is_expired' => $o->expires_at <= now(),
                    'matches' => trim((string)$o->code) === trim((string)$otpCode)
                ];
            })));

            // Find valid OTP - use direct database query with proper string comparison
            // First, get all valid OTPs for this user
            $validOtps = OtpCode::where('user_id', $user->id)
                ->where('type', 'login')
                ->where('used', false)
                ->where('expires_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info("OTP Verification - User ID: {$user->id}, Input Code: '{$otpCode}', Valid OTPs found: " . $validOtps->count());
            
            // Find matching OTP using strict string comparison
            $otp = null;
            foreach ($validOtps as $otpRecord) {
                // Compare codes as strings, ensuring both are trimmed and normalized
                $storedCode = trim((string)$otpRecord->code);
                $inputCode = trim((string)$otpCode);
                
                // Pad with zeros if needed (shouldn't be, but just in case)
                $storedCode = str_pad($storedCode, 6, '0', STR_PAD_LEFT);
                $inputCode = str_pad($inputCode, 6, '0', STR_PAD_LEFT);
                
                \Log::info("Comparing OTP - Stored: '{$storedCode}' (type: " . gettype($storedCode) . ", length: " . strlen($storedCode) . "), Input: '{$inputCode}' (type: " . gettype($inputCode) . ", length: " . strlen($inputCode) . ")");
                
                if ($storedCode === $inputCode) {
                    $otp = $otpRecord;
                    \Log::info("OTP MATCH FOUND! - User ID: {$user->id}, OTP ID: {$otp->id}");
                    break;
                }
            }
            
            if (!$otp) {
                \Log::warning("No matching OTP found - User ID: {$user->id}, Input Code: '{$otpCode}'");
            }

            if (!$otp) {
                \Log::warning("Invalid or expired OTP attempt - User ID: {$user->id}, Input Code: '{$otpCode}'");
                
                // Check if code exists but is expired or used
                $allOtps = OtpCode::where('user_id', $user->id)
                    ->where('type', 'login')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                
                $matchingOtp = null;
                foreach ($allOtps as $otpRecord) {
                    $storedCode = trim((string)$otpRecord->code);
                    $inputCode = trim((string)$otpCode);
                    $storedCode = str_pad($storedCode, 6, '0', STR_PAD_LEFT);
                    $inputCode = str_pad($inputCode, 6, '0', STR_PAD_LEFT);
                    
                    if ($storedCode === $inputCode) {
                        $matchingOtp = $otpRecord;
                        break;
                    }
                }
                
                if ($matchingOtp) {
                    if ($matchingOtp->used) {
                        \Log::info("OTP already used - User ID: {$user->id}, OTP ID: {$matchingOtp->id}");
                        return back()->withErrors(['otp_code' => 'This OTP code has already been used. Please request a new one.'])->withInput();
                    } elseif ($matchingOtp->expires_at <= now()) {
                        \Log::info("OTP expired - User ID: {$user->id}, OTP ID: {$matchingOtp->id}, Expired at: {$matchingOtp->expires_at}");
                        return back()->withErrors(['otp_code' => 'OTP code has expired. Please request a new one.'])->withInput();
                    } else {
                        \Log::warning("OTP found but not valid for unknown reason - User ID: {$user->id}, OTP ID: {$matchingOtp->id}");
                    }
                }
                
                return back()->withErrors(['otp_code' => 'Invalid OTP code. Please check and try again.'])->withInput();
            }

            // Mark OTP as used
            try {
                $otp->markAsUsed();
                \Log::info("OTP marked as used - User ID: {$user->id}, OTP ID: {$otp->id}");
            } catch (\Exception $e) {
                \Log::error("Failed to mark OTP as used - User ID: {$user->id}, OTP ID: {$otp->id}, Error: " . $e->getMessage());
                // Continue anyway - OTP is still valid
            }

            \Log::info("OTP verified successfully - User ID: {$user->id}, email: {$user->email}, OTP ID: {$otp->id}");

            // Login the user
            try {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                $request->session()->forget('otp_user_id');
                \Log::info("User logged in successfully - User ID: {$user->id}, email: {$user->email}");
            } catch (\Exception $e) {
                \Log::error("Failed to login user - User ID: {$user->id}, Error: " . $e->getMessage());
                return back()->withErrors(['otp_code' => 'Failed to complete login. Please try again.'])->withInput();
            }

            // Redirect based on role
            try {
                if ($user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
                    return redirect()->intended('/admin/role-dashboard')->with('success', 'Login successful!');
                }

                return redirect()->intended('/admin/dashboard')->with('success', 'Login successful!');
            } catch (\Exception $e) {
                \Log::error("Failed to redirect after login - User ID: {$user->id}, Error: " . $e->getMessage());
                // Fallback redirect
                return redirect('/admin/dashboard')->with('success', 'Login successful!');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('OTP validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error verifying OTP: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            \Log::error('User ID from session: ' . session('otp_user_id'));
            return back()->withErrors(['otp_code' => 'An error occurred during verification: ' . $e->getMessage() . '. Please try again.'])->withInput();
        }
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request)
    {
        try {
            $userId = session('otp_user_id');
            
            if (!$userId) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Session expired. Please login again.'], 401);
                }
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            $user = User::find($userId);
            
            if (!$user) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'User not found.'], 404);
                }
                return redirect()->route('login')->with('error', 'User not found.');
            }

            // Check rate limiting - prevent too many resend requests
            $recentOtps = OtpCode::where('user_id', $user->id)
                ->where('type', 'login')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->count();
            
            if ($recentOtps >= 3) {
                $message = 'Too many requests. Please wait a moment before requesting another OTP code.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => $message], 429);
                }
                return back()->with('error', $message);
            }

            // Generate new OTP
            $otp = OtpCode::createForUser($user, 'login', 10);
            
            // Send OTP via email
            $emailSent = $this->sendOtpEmail($user, $otp->code);
            
            if (!$emailSent) {
                \Log::error("Failed to send resend OTP email to user ID: {$user->id}");
                $message = 'Failed to send OTP email. Please try again or contact support.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => $message], 500);
                }
                return back()->with('error', $message);
            }

            \Log::info("OTP resent successfully to user ID: {$user->id}, email: {$user->email}, OTP: {$otp->code}");
            
            $message = 'A new OTP code has been sent to your email address. Please check your inbox.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => $message], 200);
            }
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Error resending OTP: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $message = 'An error occurred while resending the OTP. Please try again.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $message], 500);
            }
            return back()->with('error', $message);
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
