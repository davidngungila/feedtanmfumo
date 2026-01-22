@php
    $currentStep = 6;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 6: Additional Information</h2>
    <p>Provide additional details and preferences for your membership.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step6') }}" id="step6Form" class="ajax-form">
    @csrf
    
    <div class="form-group">
        <label for="short_bibliography" class="form-label">Short Biography (Optional)</label>
        <textarea id="short_bibliography" name="short_bibliography" class="form-input form-textarea" placeholder="A brief biography that may be published in official documents">{{ old('short_bibliography', $user->short_bibliography) }}</textarea>
        <p class="help-text">This may be used in official group documents</p>
    </div>
    
    <div class="form-group">
        <label for="introduced_by" class="form-label">Introduced By / Guarantor Name</label>
        <input type="text" id="introduced_by" name="introduced_by" value="{{ old('introduced_by', $user->introduced_by) }}" class="form-input" placeholder="Name of person who introduced you to FeedTan">
    </div>
    
    <div class="form-group">
        <label for="statement_preference" class="form-label">
            Statement Preference <span class="required">*</span>
        </label>
        <select id="statement_preference" name="statement_preference" class="form-input" required>
            <option value="">-- Select Preference --</option>
            <option value="email" {{ old('statement_preference', $user->statement_preference) == 'email' ? 'selected' : '' }}>Email</option>
            <option value="sms" {{ old('statement_preference', $user->statement_preference) == 'sms' ? 'selected' : '' }}>SMS</option>
            <option value="postal" {{ old('statement_preference', $user->statement_preference) == 'postal' ? 'selected' : '' }}>Postal Mail</option>
        </select>
        <p class="help-text">How would you like to receive your account statements?</p>
        @error('statement_preference')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step5') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 7
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

