@php
    $currentStep = 3;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 3: Address Information</h2>
    <p>Provide your current residential address details.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step3') }}" id="step3Form" class="ajax-form">
    @csrf
    
    <div class="form-group">
        <label for="address" class="form-label">
            Current Address <span class="required">*</span>
        </label>
        <textarea id="address" 
                  name="address" 
                  class="form-input form-textarea"
                  placeholder="Enter your full street address"
                  required>{{ old('address', $user->address) }}</textarea>
        @error('address')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="form-group">
            <label for="city" class="form-label">
                City <span class="required">*</span>
            </label>
            <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" class="form-input" required>
            @error('city')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="region" class="form-label">
                Region <span class="required">*</span>
            </label>
            <input type="text" id="region" name="region" value="{{ old('region', $user->region) }}" class="form-input" required>
            @error('region')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="postal_code" class="form-label">
                Postal Code
            </label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input">
        </div>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step2') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 4
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

