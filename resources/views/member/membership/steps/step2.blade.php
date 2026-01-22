@php
    $currentStep = 2;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 2: Personal Information</h2>
    <p>Please provide your personal details. All fields marked with * are required.</p>
</div>

<div class="info-box">
    <p><strong>🔒 Privacy:</strong> Your personal information is kept secure and confidential. We use this information to process your membership application and provide you with services.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step2') }}" id="step2Form" class="ajax-form">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="name" class="form-label">
                Full Name <span class="required">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $user->name) }}" 
                   class="form-input"
                   required
                   readonly>
            <p class="help-text">This field cannot be changed</p>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">
                Email Address <span class="required">*</span>
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $user->email) }}" 
                   class="form-input bg-gray-50"
                   readonly>
            <p class="help-text">This field cannot be changed</p>
        </div>
        
        <div class="form-group">
            <label for="phone" class="form-label">
                Phone Number <span class="required">*</span>
            </label>
            <input type="text" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone', $user->phone) }}" 
                   class="form-input"
                   placeholder="e.g., +255 712 345 678"
                   required>
            <p class="help-text">Include country code (e.g., +255 for Tanzania)</p>
            @error('phone')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="alternate_phone" class="form-label">
                Alternate Phone
            </label>
            <input type="text" 
                   id="alternate_phone" 
                   name="alternate_phone" 
                   value="{{ old('alternate_phone', $user->alternate_phone) }}" 
                   class="form-input"
                   placeholder="Optional backup phone number">
        </div>
        
        <div class="form-group">
            <label for="gender" class="form-label">
                Gender <span class="required">*</span>
            </label>
            <select id="gender" name="gender" class="form-input" required>
                <option value="">-- Select Gender --</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="date_of_birth" class="form-label">
                Date of Birth <span class="required">*</span>
            </label>
            <input type="date" 
                   id="date_of_birth" 
                   name="date_of_birth" 
                   value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" 
                   class="form-input"
                   max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                   required>
            <p class="help-text">You must be at least 18 years old</p>
            @error('date_of_birth')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="national_id" class="form-label">
                National ID (NIDA) <span class="required">*</span>
            </label>
            <input type="text" 
                   id="national_id" 
                   name="national_id" 
                   value="{{ old('national_id', $user->national_id) }}" 
                   class="form-input"
                   placeholder="Enter your NIDA number"
                   required>
            <p class="help-text">Your National Identification Number</p>
            @error('national_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="marital_status" class="form-label">
                Marital Status <span class="required">*</span>
            </label>
            <select id="marital_status" name="marital_status" class="form-input" required>
                <option value="">-- Select Status --</option>
                <option value="single" {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                <option value="married" {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                <option value="divorced" {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="widowed" {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
            </select>
            @error('marital_status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step1') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 3
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

