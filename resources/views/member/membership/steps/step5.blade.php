@php
    $currentStep = 5;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 5: Bank Information</h2>
    <p>Provide your banking details for transactions and payments.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step5') }}" id="step5Form" class="ajax-form">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="bank_name" class="form-label">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" class="form-input" placeholder="e.g., CRDB Bank, NMB Bank">
        </div>
        
        <div class="form-group">
            <label for="bank_branch" class="form-label">Bank Branch</label>
            <input type="text" id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $user->bank_branch) }}" class="form-input" placeholder="Branch location">
        </div>
        
        <div class="form-group">
            <label for="bank_account_number" class="form-label">Bank Account Number</label>
            <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="form-input" placeholder="Your account number">
        </div>
        
        <div class="form-group">
            <label for="payment_reference_number" class="form-label">Payment Reference Number</label>
            <input type="text" id="payment_reference_number" name="payment_reference_number" value="{{ old('payment_reference_number', $user->payment_reference_number) }}" class="form-input" placeholder="Reference for membership payment">
        </div>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step4') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 6
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

