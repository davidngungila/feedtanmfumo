@php
    $currentStep = 4;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 4: Employment Information</h2>
    <p>Tell us about your employment status and income. This information helps us assess your financial capacity.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step4') }}" id="step4Form" class="ajax-form">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="occupation" class="form-label">Occupation</label>
            <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $user->occupation) }}" class="form-input" placeholder="e.g., Teacher, Business Owner">
        </div>
        
        <div class="form-group">
            <label for="employer" class="form-label">Employer / Self Employment</label>
            <input type="text" id="employer" name="employer" value="{{ old('employer', $user->employer) }}" class="form-input" placeholder="Company name or 'Self Employed'">
        </div>
        
        <div class="form-group md:col-span-2">
            <label for="monthly_income" class="form-label">Monthly Income (TZS)</label>
            <input type="number" id="monthly_income" name="monthly_income" value="{{ old('monthly_income', $user->monthly_income) }}" class="form-input" min="0" step="0.01" placeholder="0.00">
            <p class="help-text">Your approximate monthly income in Tanzanian Shillings</p>
        </div>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step3') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 5
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

