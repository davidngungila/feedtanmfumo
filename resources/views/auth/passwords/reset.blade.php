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
    .login-container {
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

    .login-wrapper {
        max-width: 450px;
        width: 100%;
        margin: 0 auto;
    }

    /* ===== Cards ===== */
    .login-card {
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    /* ===== Form Inputs ===== */
    .input-group {
        position: relative;
        margin-bottom: 0.75rem;
    }

    .input-field {
        width: 100%;
        padding: 0.875rem 0.75rem;
        padding-left: 2.75rem;
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

    .text-gradient {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .error-message {
        font-size: 12px;
        color: #dc2626;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <svg class="w-8 h-8 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h1 class="text-2xl font-bold text-gradient">Set New Password</h1>
                </div>
                <p class="text-xs text-gray-600">Please provide a strong new password</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div class="input-group">
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        placeholder=" "
                        value="{{ $email ?? old('email') }}"
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

                <!-- Password Field -->
                <div class="input-group">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        placeholder=" "
                        class="input-field @error('password') border-red-400 @enderror"
                    >
                    <label for="password" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        New Password
                    </label>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="input-group">
                    <input 
                        id="password-confirm" 
                        name="password_confirmation" 
                        type="password" 
                        required 
                        placeholder=" "
                        class="input-field"
                    >
                    <label for="password-confirm" class="input-label">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Confirm New Password
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fields = [
            document.getElementById('email'),
            document.getElementById('password'),
            document.getElementById('password-confirm')
        ];
        
        fields.forEach(field => {
            if (field.value && field.value.trim() !== '') {
                field.classList.add('has-value');
            }
            field.addEventListener('input', function() {
                if (this.value && this.value.trim() !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
        });
    });
</script>
@endsection
