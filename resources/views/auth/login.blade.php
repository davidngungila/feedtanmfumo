@extends('layouts.app')

@push('styles')
<style>
    .login-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .login-container::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(1, 84, 37, 0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
        50% { transform: scale(1.1) rotate(180deg); opacity: 0.8; }
    }
    
    .login-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.5);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.5);
    }
    
    .input-group {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .input-group:focus-within .input-label,
    .input-group input:not(:placeholder-shown) + .input-label,
    .input-group input.has-value + .input-label,
    .input-group input[value]:not([value=""]) + .input-label {
        transform: translateY(-28px) scale(0.85);
        color: #015425;
        background: white;
        padding: 0 4px;
        left: 8px;
    }
    
    .input-label {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #6b7280;
        font-size: 14px;
        z-index: 1;
    }
    
    .input-field {
        transition: all 0.3s ease;
        background: white;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .input-field:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(1, 84, 37, 0.15);
    }
    
    .input-field:focus + .input-label {
        color: #015425;
    }
    
    .input-group:has(.input-field:invalid:not(:placeholder-shown)) .input-label {
        color: #dc2626;
    }
    
    .password-toggle {
        transition: all 0.2s ease;
    }
    
    .password-toggle:hover {
        transform: scale(1.1);
    }
    
    .btn-submit {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .btn-submit::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-submit:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .spinner {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .error-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="login-container min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full fade-in">
        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 sm:p-10">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gradient-emaoni mb-2">
                    Welcome Back
                </h1>
                <p class="text-sm text-gray-600">
                    Sign in to your FEEDTAN DIGITAL account
                </p>
            </div>

            <!-- Login Form -->
            <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-1">
                    <div class="input-group">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            placeholder=" "
                            value="{{ old('email') }}"
                            class="input-field appearance-none relative block w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl placeholder-transparent focus:outline-none focus:border-[#015425] sm:text-sm transition-all @error('email') border-red-400 error-shake @enderror"
                        >
                        <label for="email" class="input-label">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email Address
                            </span>
                        </label>
                    </div>
                    @error('email')
                        <div class="mt-1.5 flex items-start gap-1.5">
                            <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-red-600 leading-tight">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-1">
                    <div class="input-group">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            placeholder=" "
                            class="input-field appearance-none relative block w-full px-4 py-3.5 pr-12 border-2 border-gray-200 rounded-xl placeholder-transparent focus:outline-none focus:border-[#015425] sm:text-sm transition-all @error('password') border-red-400 error-shake @enderror"
                        >
                        <label for="password" class="input-label">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Password
                            </span>
                        </label>
                        <button 
                            type="button" 
                            id="togglePassword" 
                            class="password-toggle absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#015425] focus:outline-none p-1 rounded-md hover:bg-gray-100 transition-colors"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="mt-1.5 flex items-start gap-1.5">
                            <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-red-600 leading-tight">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-[#015425] focus:ring-[#015425] border-gray-300 rounded cursor-pointer transition"
                        >
                        <label for="remember" class="ml-2.5 block text-sm text-gray-700 cursor-pointer select-none">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm font-medium text-[#015425] hover:text-[#013019] transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="btn-submit group relative w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-[#015425] to-[#027a3a] hover:from-[#013019] hover:to-[#015425] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#015425] shadow-lg hover:shadow-xl transition-all duration-300"
                    >
                        <span id="submitText">Sign In</span>
                        <div id="submitSpinner" class="spinner hidden"></div>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">New to FEEDTAN?</span>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <a 
                    href="{{ route('register') }}" 
                    class="inline-flex items-center gap-2 text-sm font-medium text-[#015425] hover:text-[#013019] transition-colors group"
                >
                    <span>Create an account</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-xs text-gray-500">
            © {{ date('Y') }} FEEDTAN DIGITAL. All rights reserved.
        </p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');
        const emailField = document.getElementById('email');

        // Initialize label positions for pre-filled fields
        function initializeLabels() {
            [emailField, passwordInput].forEach(field => {
                if (field.value && field.value.trim() !== '') {
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
            });
        }

        // Form submission with loading state
        loginForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitSpinner.classList.remove('hidden');
        });

        // Handle input changes for label positioning
        [emailField, passwordInput].forEach(field => {
            field.addEventListener('input', function() {
                if (this.value && this.value.trim() !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });

        // Auto-focus first empty field
        if (!emailField.value || emailField.value.trim() === '') {
            emailField.focus();
        } else if (!passwordInput.value || passwordInput.value.trim() === '') {
            passwordInput.focus();
        }

        // Initialize on load
        initializeLabels();
    });
</script>
@endpush
@endsection

