@extends('layouts.app')

@push('styles')
<style>
    /* ===== CSS Variables ===== */
    :root {
        --primary-color: #015425;
        --primary-dark: #013019;
        --primary-light: #027a3a;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
    }

    /* ===== Container & Layout ===== */
    .register-container {
        background: linear-gradient(135deg, #013019 0%, #015425 25%, #027a3a 50%, #015425 75%, #013019 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .register-wrapper {
        max-width: 450px;
        width: 100%;
        margin: 0 auto;
    }

    /* ===== Cards ===== */
    .register-card {
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .register-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
    }

    /* ===== Form Inputs ===== */
    .input-group {
        position: relative;
        margin-bottom: 0.75rem;
    }

    .input-field {
        width: 100%;
        padding: 0.875rem 2.75rem 0.875rem 0.75rem;
        font-size: 14px;
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .input-field:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(1, 84, 37, 0.1);
    }

    .input-label {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--gray-600);
        font-size: 13px;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .input-group:focus-within .input-label,
    .input-group input:not(:placeholder-shown) + .input-label,
    .input-group input.has-value + .input-label {
        transform: translateY(-24px) scale(0.85);
        color: var(--primary-color);
        background: white;
        padding: 0 4px;
        left: 8px;
        font-weight: 600;
    }

    .input-field.pr-10 {
        padding-right: 2.75rem;
    }

    /* ===== Password Toggle ===== */
    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-600);
        cursor: pointer;
        padding: 0.25rem;
        transition: all 0.2s ease;
    }

    .password-toggle:hover {
        color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }

    /* ===== Password Strength Indicator ===== */
    .password-strength {
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }

    .password-strength.weak {
        width: 33%;
        background: #ef4444;
    }

    .password-strength.medium {
        width: 66%;
        background: #f59e0b;
    }

    .password-strength.strong {
        width: 100%;
        background: #10b981;
    }

    .password-requirements {
        margin-top: 0.5rem;
        font-size: 11px;
        color: var(--gray-600);
    }

    .requirement-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .requirement-item.valid {
        color: #10b981;
    }

    .requirement-item.invalid {
        color: var(--gray-600);
    }

    /* ===== Buttons ===== */
    .btn-submit {
        width: 100%;
        padding: 0.75rem 1.5rem;
        font-size: 14px;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(1, 84, 37, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(1, 84, 37, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .spinner {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* ===== Typography ===== */
    .text-gradient {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== Error States ===== */
    .input-field.border-red-400 {
        border-color: #f87171;
    }

    .error-message {
        font-size: 12px;
        color: #dc2626;
        margin-top: 0.25rem;
    }

    /* ===== Notifications ===== */
    .notification {
        padding: 0.5rem;
        border-radius: 0.5rem;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .notification svg {
        flex-shrink: 0;
    }

    /* ===== Animations ===== */
    .fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    .slide-up {
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 640px) {
        .register-container {
            padding: 1rem;
        }

        .register-card {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="register-container">
    <div class="register-wrapper fade-in">
        <!-- Register Card -->
        <div class="register-card">
            <!-- Header -->
            <div class="text-center mb-4 slide-up">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <svg class="w-8 h-8 text-[#015425]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gradient">Create Account</h1>
                </div>
                <p class="text-xs text-gray-600">Join FEEDTAN DIGITAL today</p>
            </div>

            <!-- Notifications -->
            @if(session('success'))
            <div class="notification bg-green-50 border border-green-200 text-green-800 slide-up">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="notification bg-red-50 border border-red-200 text-red-800 slide-up">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <!-- Register Form -->
            <form id="registerForm" action="{{ route('register') }}" method="POST" class="space-y-3">
                @csrf
                
                <!-- Name Field -->
                <div class="input-group slide-up" style="animation-delay: 0.1s">
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        autocomplete="name" 
                        required 
                        placeholder=" "
                        value="{{ old('name') }}"
                        class="input-field @error('name') border-red-400 @enderror"
                    >
                    <label for="name" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Full Name
                    </label>
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="input-group slide-up" style="animation-delay: 0.2s">
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        placeholder=" "
                        value="{{ old('email') }}"
                        class="input-field @error('email') border-red-400 @enderror"
                    >
                    <label for="email" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        Email Address
                    </label>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div class="input-group slide-up" style="animation-delay: 0.25s">
                    <input 
                        id="phone" 
                        name="phone" 
                        type="tel" 
                        autocomplete="tel" 
                        required 
                        placeholder=" "
                        value="{{ old('phone') }}"
                        class="input-field @error('phone') border-red-400 @enderror"
                    >
                    <label for="phone" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Phone Number
                    </label>
                    @error('phone')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="input-group slide-up" style="animation-delay: 0.3s">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        placeholder=" "
                        class="input-field pr-10 @error('password') border-red-400 @enderror"
                    >
                    <label for="password" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Password
                    </label>
                    <button 
                        type="button" 
                        id="togglePassword" 
                        class="password-toggle"
                        aria-label="Toggle password visibility"
                    >
                        <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeOffIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                    <div id="passwordStrength" class="password-strength hidden"></div>
                    <div id="passwordRequirements" class="password-requirements hidden">
                        <div class="requirement-item invalid" id="req-length">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="requirement-item invalid" id="req-uppercase">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>One uppercase letter</span>
                        </div>
                        <div class="requirement-item invalid" id="req-lowercase">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>One lowercase letter</span>
                        </div>
                        <div class="requirement-item invalid" id="req-number">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>One number</span>
                        </div>
                    </div>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="input-group slide-up" style="animation-delay: 0.35s">
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        placeholder=" "
                        class="input-field pr-10"
                    >
                    <label for="password_confirmation" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirm Password
                    </label>
                    <button 
                        type="button" 
                        id="togglePasswordConfirmation" 
                        class="password-toggle"
                        aria-label="Toggle password visibility"
                    >
                        <svg id="eyeIconConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="eyeOffIconConfirm" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                    <div id="passwordMatch" class="text-xs mt-1 hidden"></div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start gap-2 text-xs slide-up" style="animation-delay: 0.4s">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="w-3.5 h-3.5 text-[#015425] border-gray-300 rounded mt-0.5"
                    >
                    <label for="terms" class="text-gray-600 cursor-pointer">
                        I agree to the 
                        <a href="{{ route('terms') }}" target="_blank" class="text-[#015425] hover:underline">Terms of Service</a> 
                        and 
                        <a href="{{ route('privacy') }}" target="_blank" class="text-[#015425] hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 slide-up" style="animation-delay: 0.5s">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="btn-submit"
                    >
                        <span id="submitText">Create Account</span>
                        <div id="submitSpinner" class="spinner hidden"></div>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-2 border-t border-gray-200 slide-up" style="animation-delay: 0.6s">
                    <p class="text-xs text-gray-600 mb-1">Already have an account?</p>
                    <a href="{{ route('login') }}" class="text-xs font-medium text-[#015425] hover:underline">
                        Sign in →
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center text-xs text-gray-500 pt-4 mt-4 border-t border-gray-200">
                <p>© {{ date('Y') }} FEEDTAN DIGITAL. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        const phoneField = document.getElementById('phone');
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        const eyeOffIconConfirm = document.getElementById('eyeOffIconConfirm');
        const registerForm = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordRequirements = document.getElementById('passwordRequirements');
        const passwordMatch = document.getElementById('passwordMatch');

        // Initialize label positions
        function initializeLabels() {
            [nameField, emailField, phoneField, passwordInput, passwordConfirmation].forEach(field => {
                if (field && field.value && field.value.trim() !== '') {
                    field.classList.add('has-value');
                }
            });
        }

        // Password visibility toggle
        if (togglePassword) {
            togglePassword.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('hidden');
                eyeOffIcon.classList.toggle('hidden');
                passwordInput.focus();
            });
        }

        if (togglePasswordConfirmation) {
            togglePasswordConfirmation.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmation.setAttribute('type', type);
                eyeIconConfirm.classList.toggle('hidden');
                eyeOffIconConfirm.classList.toggle('hidden');
                passwordConfirmation.focus();
            });
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
            };

            // Update requirement indicators
            document.getElementById('req-length').className = requirements.length ? 'requirement-item valid' : 'requirement-item invalid';
            document.getElementById('req-uppercase').className = requirements.uppercase ? 'requirement-item valid' : 'requirement-item invalid';
            document.getElementById('req-lowercase').className = requirements.lowercase ? 'requirement-item valid' : 'requirement-item invalid';
            document.getElementById('req-number').className = requirements.number ? 'requirement-item invalid' : 'requirement-item invalid';

            if (requirements.length) strength++;
            if (requirements.uppercase) strength++;
            if (requirements.lowercase) strength++;
            if (requirements.number) strength++;

            if (password.length === 0) {
                passwordStrength.classList.add('hidden');
                passwordRequirements.classList.add('hidden');
                return;
            }

            passwordStrength.classList.remove('hidden');
            passwordRequirements.classList.remove('hidden');

            if (strength <= 2) {
                passwordStrength.className = 'password-strength weak';
            } else if (strength === 3) {
                passwordStrength.className = 'password-strength medium';
            } else {
                passwordStrength.className = 'password-strength strong';
            }
        }

        // Password match checker
        function checkPasswordMatch() {
            if (passwordConfirmation.value.length === 0) {
                passwordMatch.classList.add('hidden');
                return;
            }

            passwordMatch.classList.remove('hidden');
            if (passwordInput.value === passwordConfirmation.value) {
                passwordMatch.textContent = '✓ Passwords match';
                passwordMatch.className = 'text-xs mt-1 text-green-600';
                passwordConfirmation.style.borderColor = '#10b981';
            } else {
                passwordMatch.textContent = '✗ Passwords do not match';
                passwordMatch.className = 'text-xs mt-1 text-red-600';
                passwordConfirmation.style.borderColor = '#f87171';
            }
        }

        // Password input handlers
        passwordInput.addEventListener('input', function() {
            if (this.value && this.value.trim() !== '') {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        passwordConfirmation.addEventListener('input', function() {
            if (this.value && this.value.trim() !== '') {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
            checkPasswordMatch();
        });

        // Other input handlers
        [nameField, emailField, phoneField].forEach(field => {
            if (field) {
                field.addEventListener('input', function() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            }
        });

        // Form submission
        registerForm.addEventListener('submit', function(e) {
            if (passwordInput.value !== passwordConfirmation.value) {
                e.preventDefault();
                passwordMatch.textContent = '✗ Passwords must match';
                passwordMatch.className = 'text-xs mt-1 text-red-600';
                passwordMatch.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitSpinner.classList.remove('hidden');
        });

        // Auto-focus
        if (!nameField.value || nameField.value.trim() === '') {
            setTimeout(() => nameField.focus(), 100);
        }

        // Auto-hide notifications
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        });

        initializeLabels();
    });
</script>
@endpush
@endsection
