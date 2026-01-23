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
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Generate new OTP
        $otp = OtpCode::createForUser($user, 'login', 10);
        
        // Send OTP via email
        $this->sendOtpEmail($user, $otp->code);

        return back()->with('success', 'A new OTP code has been sent to your email.');
    }

    /**
     * Send OTP code via email
     */
    protected function sendOtpEmail(User $user, string $code): void
    {
        try {
            $orgInfo = $this->emailService->getOrganizationInfo();
            $address = $this->emailService->getFormattedAddress();
            
            $subject = "Login OTP Code - {$orgInfo['name']}";
            $message = "Dear {$user->name},

You have requested to login to your account. Please use the following OTP code to complete your login:

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

YOUR OTP CODE: {$code}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

This code will expire in 10 minutes.

If you did not request this code, please ignore this email or contact us immediately.

Best regards,
{$address}";

            Mail::raw($message, function ($mail) use ($user, $subject, $orgInfo) {
                $mail->to($user->email, $user->name)
                     ->subject($subject)
                     ->from($orgInfo['from_email'], $orgInfo['from_name']);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
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
