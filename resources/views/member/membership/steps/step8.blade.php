@php
    $currentStep = 8;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 8: Group Information (If Applicable)</h2>
    <p>If you are applying as part of a registered group, please provide the group details below.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step8') }}" id="step8Form" class="ajax-form">
    @csrf
    
    <div class="form-group">
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" name="is_group_registered" value="1" 
                   {{ old('is_group_registered', $user->is_group_registered) ? 'checked' : '' }}
                   class="w-5 h-5 text-[#015425] border-gray-300 rounded focus:ring-[#015425]">
            <span class="ml-3 text-gray-700 font-medium">Is your group registered with Government Authorities?</span>
        </label>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="group_name" class="form-label">Group Name</label>
            <input type="text" id="group_name" name="group_name" value="{{ old('group_name', $user->group_name) }}" class="form-input" placeholder="Official group name">
        </div>
        
        <div class="form-group">
            <label for="group_leaders" class="form-label">Group Leaders</label>
            <input type="text" id="group_leaders" name="group_leaders" value="{{ old('group_leaders', $user->group_leaders) }}" class="form-input" placeholder="Names of group leaders">
        </div>
        
        <div class="form-group">
            <label for="group_bank_account" class="form-label">Group Bank Account</label>
            <input type="text" id="group_bank_account" name="group_bank_account" value="{{ old('group_bank_account', $user->group_bank_account) }}" class="form-input" placeholder="Group account number">
        </div>
    </div>
    
    <div class="form-group">
        <label for="group_contacts" class="form-label">Group Contacts (Email, Mobile, Address)</label>
        <textarea id="group_contacts" name="group_contacts" class="form-input form-textarea" placeholder="Group contact information">{{ old('group_contacts', $user->group_contacts) }}</textarea>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step7') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 9
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

