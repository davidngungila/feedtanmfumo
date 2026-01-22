@php
    $currentStep = 1;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 1: Select Membership Type</h2>
    <p>Choose the membership type that best fits your needs. Each type has different benefits and requirements.</p>
</div>

<div class="info-box">
    <p><strong>💡 Tip:</strong> Review each membership type carefully. You can view detailed information about fees, benefits, and requirements for each option below.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step1') }}" id="step1Form" class="ajax-form">
    @csrf
    
    <div class="form-group">
        <label class="form-label">
            Membership Type <span class="required">*</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @foreach($membershipTypes as $type)
                <label class="relative cursor-pointer block">
                    <input type="radio" 
                           name="membership_type_id" 
                           value="{{ $type->id }}" 
                           class="absolute opacity-0 w-0 h-0"
                           {{ old('membership_type_id', $user->membership_type_id) == $type->id ? 'checked' : '' }}
                           required>
                    <div class="border-2 border-gray-300 rounded-xl p-6 hover:border-[#015425] transition-all duration-300 hover:shadow-lg membership-card">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $type->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $type->description }}</p>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center transition-all radio-indicator">
                                    <svg class="w-4 h-4 text-white opacity-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 pt-4 border-t border-gray-200">
                            @if($type->entrance_fee > 0)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Entrance Fee:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($type->entrance_fee) }} TZS</span>
                            </div>
                            @endif
                            
                            @if($type->capital_contribution > 0)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Capital Contribution:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($type->capital_contribution) }} TZS</span>
                            </div>
                            @endif
                            
                            @if($type->minimum_shares > 0)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Minimum Shares:</span>
                                <span class="font-semibold text-gray-900">{{ $type->minimum_shares }}</span>
                            </div>
                            @endif
                        </div>
                        
                        @if($type->features)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Key Features:</p>
                            <ul class="text-xs text-gray-600 space-y-1">
                                @foreach(explode(',', $type->features) as $feature)
                                    <li class="flex items-start">
                                        <svg class="w-3 h-3 text-green-600 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ trim($feature) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>
        @error('membership_type_id')
            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <div></div>
        <button type="submit" class="btn-primary">
            Continue to Step 2
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>

@push('styles')
<style>
    /* Ensure peer-checked works for radio buttons */
    input[type="radio"]:checked + div,
    input[type="radio"]:checked ~ div {
        border-color: #015425 !important;
        background: linear-gradient(to bottom right, #f0fdf4 0%, #d1fae5 100%) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    
    input[type="radio"]:checked + div .rounded-full,
    input[type="radio"]:checked ~ div .rounded-full {
        border-color: #015425 !important;
        background-color: #015425 !important;
    }
    
    input[type="radio"]:checked + div .rounded-full svg,
    input[type="radio"]:checked ~ div .rounded-full svg {
        opacity: 1 !important;
    }
    
    /* Make entire label clickable */
    label.cursor-pointer {
        display: block;
        width: 100%;
    }
    
    label.cursor-pointer:hover > div {
        border-color: #015425;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('step1Form');
    const radioButtons = document.querySelectorAll('input[name="membership_type_id"]');
    
    // Function to update visual state
    function updateVisualState() {
        radioButtons.forEach(function(radio) {
            const label = radio.closest('label');
            const card = label ? label.querySelector('.membership-card') : null;
            const indicator = label ? label.querySelector('.radio-indicator') : null;
            const checkmark = indicator ? indicator.querySelector('svg') : null;
            
            if (radio.checked) {
                if (card) {
                    card.style.borderColor = '#015425';
                    card.style.background = 'linear-gradient(to bottom right, #f0fdf4 0%, #d1fae5 100%)';
                    card.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
                }
                if (indicator) {
                    indicator.style.borderColor = '#015425';
                    indicator.style.backgroundColor = '#015425';
                }
                if (checkmark) {
                    checkmark.style.opacity = '1';
                }
            } else {
                if (card) {
                    card.style.borderColor = '#d1d5db';
                    card.style.background = '';
                    card.style.boxShadow = '';
                }
                if (indicator) {
                    indicator.style.borderColor = '#d1d5db';
                    indicator.style.backgroundColor = 'white';
                }
                if (checkmark) {
                    checkmark.style.opacity = '0';
                }
            }
        });
    }
    
    // Ensure radio buttons are clickable
    radioButtons.forEach(function(radio) {
        const label = radio.closest('label');
        
        if (label) {
            // Make entire label clickable
            label.addEventListener('click', function(e) {
                // Only handle if not clicking directly on the radio button
                if (e.target !== radio && e.target.type !== 'radio') {
                    radio.checked = true;
                    updateVisualState();
                }
            });
        }
        
        // Update styles on change
        radio.addEventListener('change', function() {
            updateVisualState();
        });
        
        // Also listen for click on radio itself
        radio.addEventListener('click', function() {
            updateVisualState();
        });
    });
    
    // Form validation - will be handled by AJAX form handler
    form.addEventListener('submit', function(e) {
        const selected = document.querySelector('input[name="membership_type_id"]:checked');
        if (!selected) {
            e.preventDefault();
            // Use alert if showToast is not available yet
            if (typeof showToast === 'function') {
                showToast('error', 'Validation Error', 'Please select a membership type to continue.');
            } else {
                alert('Please select a membership type to continue.');
            }
            return false;
        }
    });
    
    // Initialize checked state on page load
    updateVisualState();
});
</script>
@endpush
@endsection

