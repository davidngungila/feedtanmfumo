@php
    $currentStep = 10;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 10: Additional Options & Final Submission</h2>
    <p>Review your options and submit your membership application for approval.</p>
</div>

<div class="info-box">
    <p><strong>🎉 Almost Done!</strong> This is the final step. Review all your information and submit your application. You'll receive a confirmation once submitted.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step10') }}" id="step10Form">
    @csrf
    
    <div class="form-group">
        <label class="flex items-start cursor-pointer">
            <input type="checkbox" name="wants_ordinary_membership" value="1" 
                   {{ old('wants_ordinary_membership', $user->wants_ordinary_membership) ? 'checked' : '' }}
                   class="w-5 h-5 text-[#015425] border-gray-300 rounded focus:ring-[#015425] mt-1">
            <span class="ml-3 text-gray-700">
                <span class="font-medium">Would you like to be considered for ordinary membership?</span>
                <p class="text-sm text-gray-600 mt-1">Ordinary membership may provide additional benefits and voting rights in the organization.</p>
            </span>
        </label>
    </div>
    
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg mt-6">
        <h3 class="font-bold text-blue-900 mb-2">Application Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-blue-800"><strong>Membership Type:</strong> {{ $user->membershipType->name ?? 'Not selected' }}</p>
                <p class="text-blue-800"><strong>Membership Code:</strong> {{ $user->membership_code ?? 'Pending' }}</p>
            </div>
            <div>
                <p class="text-blue-800"><strong>Steps Completed:</strong> {{ count($user->membership_application_completed_steps ?? []) }} / 10</p>
                <p class="text-blue-800"><strong>Status:</strong> Ready for Submission</p>
            </div>
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-6 border-t border-gray-200 mt-6">
        <div class="flex gap-3 w-full sm:w-auto">
            <a href="{{ route('member.membership.step9') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Previous
            </a>
            <a href="{{ route('member.membership.preview') }}" class="btn-secondary flex-1 sm:flex-none text-center" target="_blank">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Preview
            </a>
        </div>
        <button type="submit" class="btn-primary bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 w-full sm:w-auto">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Submit Application
        </button>
    </div>
</form>

@push('styles')
<style>
    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.9);
        transition: transform 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-overlay.show .modal-content {
        transform: scale(1);
    }

    .modal-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .modal-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #015425;
        margin-bottom: 0.5rem;
    }

    .modal-header p {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .modal-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-icon svg {
        width: 32px;
        height: 32px;
        color: #f59e0b;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .modal-btn {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        font-size: 0.95rem;
    }

    .modal-btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .modal-btn-cancel:hover {
        background: #e5e7eb;
    }

    .modal-btn-confirm {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(1, 84, 37, 0.3);
    }

    .modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(1, 84, 37, 0.4);
    }

    /* Progress/Splash Screen Styles */
    .progress-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #013019 0%, #015425 50%, #027a3a 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 20000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .progress-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .progress-container {
        text-align: center;
        color: white;
        max-width: 500px;
        width: 90%;
    }

    .progress-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        position: relative;
    }

    .progress-icon svg {
        width: 100%;
        height: 100%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .progress-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .progress-message {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 4px;
        width: 0%;
        transition: width 0.3s ease;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }

    .progress-percentage {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .progress-status {
        font-size: 0.95rem;
        opacity: 0.8;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('step10Form');
    if (!form) return;

    // Create modal
    const modalHTML = `
        <div id="confirmModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3>Confirm Submission</h3>
                    <p>Are you sure you want to submit your membership application? You will not be able to edit it after submission.</p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn modal-btn-cancel" id="cancelBtn">Cancel</button>
                    <button type="button" class="modal-btn modal-btn-confirm" id="confirmBtn">Yes, Submit</button>
                </div>
            </div>
        </div>
    `;

    // Create progress overlay
    const progressHTML = `
        <div id="progressOverlay" class="progress-overlay">
            <div class="progress-container">
                <div class="progress-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="progress-title">Submitting Application</div>
                <div class="progress-message">Please wait while we process your membership application...</div>
                <div class="progress-percentage" id="progressPercentage">0%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
                <div class="progress-status" id="progressStatus">Initializing...</div>
            </div>
        </div>
    `;

    // Insert modals into body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    document.body.insertAdjacentHTML('beforeend', progressHTML);

    const modal = document.getElementById('confirmModal');
    const progressOverlay = document.getElementById('progressOverlay');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    const progressStatus = document.getElementById('progressStatus');

    let isSubmitting = false;

    // Prevent auto-setup from attaching to this form
    form.dataset.noAutoSetup = 'true';
    
    // Show modal on form submit - use capture phase to run first
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        e.preventDefault();
        e.stopPropagation();
        modal.classList.add('show');
        return false;
    }, true); // Use capture phase to ensure this runs before other handlers

    // Cancel button
    cancelBtn.addEventListener('click', function() {
        modal.classList.remove('show');
    });

    // Close modal on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    // Confirm button - start submission
    confirmBtn.addEventListener('click', function() {
        modal.classList.remove('show');
        isSubmitting = true;
        
        // Show progress overlay
        setTimeout(() => {
            progressOverlay.classList.add('show');
            startProgress();
        }, 300);
    });

    // Progress animation
    function startProgress() {
        let progress = 0;
        const duration = 3000; // 3 seconds
        const interval = 50; // Update every 50ms
        const increment = (100 / (duration / interval));

        const statusMessages = [
            { percent: 10, message: 'Validating information...' },
            { percent: 25, message: 'Saving application data...' },
            { percent: 40, message: 'Sending confirmation email...' },
            { percent: 60, message: 'Sending SMS notification...' },
            { percent: 80, message: 'Finalizing submission...' },
            { percent: 95, message: 'Almost done...' },
            { percent: 100, message: 'Submission complete!' }
        ];

        const progressInterval = setInterval(() => {
            progress += increment;
            
            if (progress >= 100) {
                progress = 100;
                clearInterval(progressInterval);
            }

            // Update progress bar
            progressBar.style.width = progress + '%';
            progressPercentage.textContent = Math.round(progress) + '%';

            // Update status message
            const currentStatus = statusMessages.find(s => progress <= s.percent) || statusMessages[statusMessages.length - 1];
            if (currentStatus) {
                progressStatus.textContent = currentStatus.message;
            }

            // Submit form at 50%
            if (Math.round(progress) === 50 && !form.dataset.submitted) {
                form.dataset.submitted = 'true';
                // Create a hidden form to submit
                const formData = new FormData(form);
                formData.append('_ajax', '1');
                
                // Get CSRF token from form or meta tag
                const csrfToken = formData.get('_token') || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Wait for progress to reach 100%
                    setTimeout(() => {
                        progressOverlay.classList.remove('show');
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    progressStatus.textContent = 'An error occurred. Please try again.';
                    setTimeout(() => {
                        progressOverlay.classList.remove('show');
                        window.location.reload();
                    }, 2000);
                });
            }
        }, interval);
    }
});
</script>
@endpush
@endsection

