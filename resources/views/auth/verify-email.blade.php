@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-wrapper fade-in">
        <!-- Verification Card -->
        <div class="register-card">
            <!-- Header -->
            <div class="text-center mb-4 slide-up">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <svg class="w-8 h-8 text-[#015425]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gradient">Verify Your Email</h1>
                </div>
                <p class="text-xs text-gray-600">Check your inbox for verification link</p>
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

            <!-- Verification Message -->
            <div class="text-center mb-6 slide-up" style="animation-delay: 0.1s">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Email Verification Required</h2>
                <p class="text-sm text-gray-600 mb-4">
                    We've sent a verification link to your email address:
                </p>
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-sm font-mono text-gray-700">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 slide-up" style="animation-delay: 0.2s">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Next Steps:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-700">
                            <li>Check your email inbox</li>
                            <li>Click the verification link in the email</li>
                            <li>You'll be automatically redirected to your dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Resend Verification Form -->
            <form action="{{ route('verification.resend') }}" method="POST" class="space-y-4 slide-up" style="animation-delay: 0.3s">
                @csrf
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-3">Didn't receive the email?</p>
                    <button type="submit" class="btn-submit inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Resend Verification Email
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center text-xs text-gray-500 pt-4 mt-4 border-t border-gray-200">
                <p>© {{ date('Y') }} FEEDTAN DIGITAL. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Reuse register styles */
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

    .text-gradient {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .btn-submit {
        width: 100%;
        padding: 0.75rem 1.5rem;
        font-size: 14px;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide notifications
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        });
    });
</script>
@endpush
@endsection
