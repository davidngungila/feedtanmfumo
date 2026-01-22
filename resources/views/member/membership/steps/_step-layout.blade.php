@php
    $currentStep = $currentStep ?? 1;
    $completedSteps = $user->membership_application_completed_steps ?? [];
    $stepTitles = [
        1 => 'Membership Type',
        2 => 'Personal Info',
        3 => 'Address',
        4 => 'Employment',
        5 => 'Bank Info',
        6 => 'Additional',
        7 => 'Beneficiaries',
        8 => 'Group Info',
        9 => 'Documents',
        10 => 'Options',
    ];
@endphp

@extends('layouts.member')

@section('page-title', 'Membership Application - Step ' . $currentStep)

@push('styles')
<style>
    .step-progress-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 1rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .step-progress-container {
            padding: 2rem;
        }
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 1rem;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 0.5rem;
    }
    
    .step-indicator::-webkit-scrollbar {
        display: none;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 18px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, #e5e7eb 0%, #e5e7eb 100%);
        z-index: 0;
        border-radius: 2px;
    }
    
    @media (max-width: 767px) {
        .step-indicator::before {
            top: 15px;
            height: 2px;
        }
    }
    
    .step-progress-line {
        position: absolute;
        top: 18px;
        left: 0;
        height: 3px;
        background: linear-gradient(to right, #10b981 0%, #015425 100%);
        z-index: 1;
        border-radius: 2px;
        transition: width 0.5s ease;
        max-width: 100%;
    }
    
    @media (max-width: 767px) {
        .step-progress-line {
            top: 15px;
            height: 2px;
        }
    }
    
    .step-item {
        position: relative;
        z-index: 2;
        flex: 0 0 auto;
        text-align: center;
        min-width: 60px;
        padding: 0 4px;
    }
    
    @media (min-width: 768px) {
        .step-item {
            flex: 1;
            min-width: auto;
            padding: 0;
        }
    }
    
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    @media (min-width: 768px) {
        .step-number {
            width: 42px;
            height: 42px;
            font-size: 16px;
        }
    }
    
    .step-item.completed .step-number {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: white;
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }
    
    .step-item.completed .step-number::after {
        content: '✓';
        font-size: 18px;
    }
    
    @media (min-width: 768px) {
        .step-item.completed .step-number::after {
            font-size: 20px;
        }
    }
    
    .step-item.active .step-number {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        border-color: #015425;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(1, 84, 37, 0.4);
    }
    
    @media (max-width: 767px) {
        .step-item.active .step-number {
            transform: scale(1.05);
        }
    }
    
    .step-item.locked .step-number {
        background: #f3f4f6;
        border-color: #d1d5db;
        color: #9ca3af;
        cursor: not-allowed;
    }
    
    .step-title {
        font-size: 0.6rem;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.3s;
        line-height: 1.2;
        word-break: break-word;
    }
    
    @media (min-width: 768px) {
        .step-title {
            font-size: 0.7rem;
        }
    }
    
    .step-item.active .step-title {
        color: #015425;
        font-weight: 700;
        font-size: 0.65rem;
    }
    
    @media (min-width: 768px) {
        .step-item.active .step-title {
            font-size: 0.75rem;
        }
    }
    
    .step-item.completed .step-title {
        color: #10b981;
        font-weight: 600;
    }
    
    .step-item.locked .step-title {
        color: #9ca3af;
    }
    
    .step-content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    
    @media (min-width: 768px) {
        .step-content-card {
            padding: 2.5rem;
        }
    }
    
    .step-header {
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .step-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #015425;
        margin-bottom: 0.5rem;
    }
    
    @media (min-width: 768px) {
        .step-header h2 {
            font-size: 1.75rem;
        }
    }
    
    .step-header p {
        color: #6b7280;
        font-size: 0.85rem;
    }
    
    @media (min-width: 768px) {
        .step-header p {
            font-size: 0.95rem;
        }
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #015425;
        box-shadow: 0 0 0 3px rgba(1, 84, 37, 0.1);
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(1, 84, 37, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(1, 84, 37, 0.4);
    }
    
    .btn-secondary {
        background: white;
        color: #374151;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    
    .info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .info-box p {
        color: #1e40af;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .help-text {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    /* Toast Notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid;
        animation: slideInRight 0.3s ease-out;
        min-width: 300px;
    }
    
    .toast.success {
        border-left-color: #10b981;
    }
    
    .toast.error {
        border-left-color: #ef4444;
    }
    
    .toast.info {
        border-left-color: #3b82f6;
    }
    
    .toast-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
    }
    
    .toast.success .toast-icon {
        color: #10b981;
    }
    
    .toast.error .toast-icon {
        color: #ef4444;
    }
    
    .toast.info .toast-icon {
        color: #3b82f6;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .toast-message {
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .toast-close {
        flex-shrink: 0;
        cursor: pointer;
        color: #9ca3af;
        padding: 0.25rem;
    }
    
    .toast-close:hover {
        color: #374151;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .toast.hiding {
        animation: slideOutRight 0.3s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Progress Indicator -->
    <div class="step-progress-container">
        <div class="step-indicator" id="stepIndicator">
            <div class="step-progress-line" style="width: {{ (($currentStep - 1) / 9) * 100 }}%; max-width: 100%;"></div>
            @for($i = 1; $i <= 10; $i++)
                @php
                    $isCompleted = in_array($i, $completedSteps);
                    $isActive = $i == $currentStep;
                    $isLocked = !$isCompleted && !$isActive && $i > 1 && !in_array($i - 1, $completedSteps);
                @endphp
                <div class="step-item {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }} {{ $isLocked ? 'locked' : '' }}" data-step="{{ $i }}">
                    <div class="step-number">
                        @if(!$isCompleted)
                            {{ $i }}
                        @endif
                    </div>
                    <div class="step-title">
                        <span class="hidden sm:inline">{{ $stepTitles[$i] ?? 'Step ' . $i }}</span>
                        <span class="sm:hidden">{{ $i }}</span>
                    </div>
                </div>
            @endfor
        </div>
        
        <div class="text-center mt-4">
            <p class="text-xs sm:text-sm text-gray-600">
                <span class="font-semibold text-[#015425]">Step {{ $currentStep }} of 10</span>
                <span class="mx-1 sm:mx-2 hidden sm:inline">•</span>
                <span class="block sm:inline mt-1 sm:mt-0">{{ count($completedSteps) }} steps completed</span>
            </p>
            <p class="text-xs text-gray-500 mt-1 sm:hidden">
                <span class="font-medium">{{ $stepTitles[$currentStep] ?? 'Step ' . $currentStep }}</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Step Content -->
    <div class="step-content-card">
        @yield('step-content')
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="toast-container"></div>

@push('scripts')
<script>
// Toast Notification System
function showToast(type, title, message, duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icons = {
        success: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        error: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        info: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };
    
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || icons.info}</div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <div class="toast-close" onclick="this.closest('.toast').remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after duration
    setTimeout(() => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Show success message from session
@if(session('success'))
    showToast('success', 'Success!', '{{ session('success') }}');
@endif

@if(session('error'))
    showToast('error', 'Error!', '{{ session('error') }}');
@endif

// AJAX Form Submission Handler
function setupAjaxForm(formId, successCallback) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    // Check if form already has submit handler
    const existingHandler = form.dataset.ajaxSetup;
    if (existingHandler === 'true') return;
    form.dataset.ajaxSetup = 'true';
    
    form.addEventListener('submit', function(e) {
        // Don't prevent default if form has HTML5 validation errors
        if (!form.checkValidity()) {
            return true; // Let browser handle validation
        }
        
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton ? submitButton.innerHTML : '';
        
        // Disable submit button
        if (submitButton) {
            submitButton.disabled = true;
            const spinner = '<span class="spinner"></span>';
            submitButton.innerHTML = spinner + ' Saving...';
        }
        
        // Add AJAX header
        formData.append('_ajax', '1');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'An error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('success', 'Success!', data.message || 'Step saved successfully!');
                
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else if (successCallback) {
                    successCallback(data);
                }
            } else {
                showToast('error', 'Error!', data.message || 'An error occurred. Please try again.');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error!', error.message || 'An error occurred. Please try again.');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });
}

// Initialize AJAX forms on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-setup forms with class 'ajax-form' or id starting with 'step'
    const forms = document.querySelectorAll('form.ajax-form, form[id^="step"]');
    forms.forEach(form => {
        if (form.id) {
            setupAjaxForm(form.id);
        }
    });
});

// Add spinner CSS
const style = document.createElement('style');
style.textContent = `
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to active step on mobile
    const stepIndicator = document.getElementById('stepIndicator');
    const activeStep = stepIndicator.querySelector('.step-item.active');
    
    if (activeStep && window.innerWidth < 768) {
        const stepIndicatorRect = stepIndicator.getBoundingClientRect();
        const activeStepRect = activeStep.getBoundingClientRect();
        const scrollPosition = activeStep.offsetLeft - (stepIndicatorRect.width / 2) + (activeStepRect.width / 2);
        
        stepIndicator.scrollTo({
            left: Math.max(0, scrollPosition),
            behavior: 'smooth'
        });
    }
    
    // Add scroll indicators on mobile
    if (window.innerWidth < 768) {
        let scrollTimeout;
        stepIndicator.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            stepIndicator.classList.add('scrolling');
            scrollTimeout = setTimeout(function() {
                stepIndicator.classList.remove('scrolling');
            }, 150);
        });
    }
});
</script>
@endpush
@endsection

