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

<form method="POST" action="{{ route('member.membership.store-step10') }}" id="step10Form" class="ajax-form">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('step10Form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to submit your membership application? You will not be able to edit it after submission.')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection

