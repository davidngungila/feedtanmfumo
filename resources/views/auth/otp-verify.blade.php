@extends('layouts.app')

@section('page-title', 'Verify OTP')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#015425] to-[#027a3a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 bg-white rounded-full flex items-center justify-center mb-4">
                <span class="text-2xl font-bold text-[#015425]">FD</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Verify OTP Code
            </h2>
            <p class="mt-2 text-center text-sm text-white text-opacity-90">
                Enter the 6-digit code sent to your email
            </p>
        </div>
        
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <form class="mt-8 space-y-6 bg-white rounded-lg shadow-xl p-8" action="{{ route('otp.verify') }}" method="POST">
            @csrf
            
            <div>
                <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2">
                    OTP Code
                </label>
                <input 
                    id="otp_code" 
                    name="otp_code" 
                    type="text" 
                    maxlength="6" 
                    pattern="[0-9]{6}"
                    required 
                    autofocus
                    class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-[#015425] focus:border-[#015425] focus:z-10 text-center text-2xl font-bold tracking-widest"
                    placeholder="000000"
                >
                @error('otp_code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-[#015425] hover:bg-[#013019] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#015425] transition"
                >
                    Verify OTP
                </button>
            </div>

            <div class="text-center">
                <form id="resendOtpForm" action="{{ route('otp.resend') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" id="resendOtpBtn" class="text-sm text-[#015425] hover:text-[#013019] font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="resendOtpText">Resend OTP Code</span>
                    </button>
                </form>
                <p id="resendCountdown" class="text-xs text-gray-500 mt-2 hidden"></p>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Back to Login
                </a>
            </div>
        </form>

        <div class="text-center text-white text-opacity-75 text-xs">
            <p>The OTP code will expire in 10 minutes.</p>
            <p class="mt-1">If you didn't receive the code, check your spam folder or click "Resend OTP Code".</p>
        </div>
    </div>
</div>

<script>
    // Auto-focus and format OTP input with auto-verification
    document.addEventListener('DOMContentLoaded', function() {
        const otpInput = document.getElementById('otp_code');
        const otpForm = otpInput.closest('form');
        const submitButton = otpForm.querySelector('button[type="submit"]');
        let isSubmitting = false;
        
        // Ensure input only accepts numbers
        otpInput.addEventListener('input', function(e) {
            // Only allow numbers
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            // Auto-submit when exactly 6 digits are entered
            if (value.length === 6 && !isSubmitting) {
                isSubmitting = true;
                
                // Visual feedback - disable input and show loading
                otpInput.disabled = true;
                otpInput.classList.add('opacity-75', 'cursor-not-allowed');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Verifying...';
                    submitButton.classList.add('opacity-75', 'cursor-not-allowed');
                }
                
                // Small delay to ensure value is set, then submit
                setTimeout(function() {
                    otpForm.submit();
                }, 150);
            }
        });
        
        // Handle paste events
        otpInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = pasted.replace(/[^0-9]/g, '').substring(0, 6);
            otpInput.value = numbers;
            
            // Auto-submit if 6 digits pasted
            if (numbers.length === 6 && !isSubmitting) {
                isSubmitting = true;
                otpInput.disabled = true;
                otpInput.classList.add('opacity-75', 'cursor-not-allowed');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Verifying...';
                    submitButton.classList.add('opacity-75', 'cursor-not-allowed');
                }
                
                setTimeout(function() {
                    otpForm.submit();
                }, 150);
            }
        });
        
        // Prevent manual submission if less than 6 digits
        otpForm.addEventListener('submit', function(e) {
            const value = otpInput.value.replace(/[^0-9]/g, '');
            if (value.length !== 6) {
                e.preventDefault();
                alert('Please enter a complete 6-digit OTP code.');
                otpInput.focus();
                otpInput.select();
                return false;
            }
        });
        
        // Focus on input when page loads
        otpInput.focus();
        
        // Add inputmode for mobile numeric keyboard
        otpInput.setAttribute('inputmode', 'numeric');
        otpInput.setAttribute('autocomplete', 'one-time-code');
        
        // Resend OTP functionality with rate limiting
        const resendOtpForm = document.getElementById('resendOtpForm');
        const resendOtpBtn = document.getElementById('resendOtpBtn');
        const resendOtpText = document.getElementById('resendOtpText');
        const resendCountdown = document.getElementById('resendCountdown');
        let resendCooldown = 0;
        let countdownInterval = null;
        
        // Check if there's a cooldown from previous session
        const lastResendTime = sessionStorage.getItem('lastOtpResend');
        if (lastResendTime) {
            const timeSinceLastResend = Math.floor((Date.now() - parseInt(lastResendTime)) / 1000);
            if (timeSinceLastResend < 60) {
                resendCooldown = 60 - timeSinceLastResend;
                startCountdown();
            }
        }
        
        function startCountdown() {
            if (resendCooldown > 0) {
                resendOtpBtn.disabled = true;
                resendCountdown.classList.remove('hidden');
                resendCountdown.textContent = `Please wait ${resendCooldown} seconds before requesting a new code.`;
                
                countdownInterval = setInterval(function() {
                    resendCooldown--;
                    if (resendCooldown > 0) {
                        resendCountdown.textContent = `Please wait ${resendCooldown} seconds before requesting a new code.`;
                    } else {
                        clearInterval(countdownInterval);
                        resendOtpBtn.disabled = false;
                        resendCountdown.classList.add('hidden');
                        resendOtpText.textContent = 'Resend OTP Code';
                    }
                }, 1000);
            }
        }
        
        resendOtpForm.addEventListener('submit', function(e) {
            if (resendCooldown > 0) {
                e.preventDefault();
                return false;
            }
            
            // Disable button and show loading state
            resendOtpBtn.disabled = true;
            resendOtpText.textContent = 'Sending...';
            
            // Set cooldown after successful submission
            setTimeout(function() {
                resendCooldown = 60; // 60 seconds cooldown
                sessionStorage.setItem('lastOtpResend', Date.now().toString());
                startCountdown();
            }, 1000);
        });
    });
</script>
@endsection

