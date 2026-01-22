@php
    $currentStep = 7;
    $beneficiaries = $beneficiaries ?? [];
    if (empty($beneficiaries)) {
        $beneficiaries = [['name' => '', 'relationship' => '', 'allocation' => '', 'contact' => '']];
    }
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 7: Beneficiaries Information</h2>
    <p>Provide beneficiary details in case of unfortunate events. You can add multiple beneficiaries.</p>
</div>

<div class="info-box">
    <p><strong>📋 Important:</strong> Beneficiaries will receive benefits according to the allocation percentage you specify. Total allocation should not exceed 100%.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step7') }}" id="step7Form" class="ajax-form">
    @csrf
    
    <div id="beneficiaries-container">
        @foreach($beneficiaries as $index => $beneficiary)
        <div class="beneficiary-item border-2 border-gray-200 rounded-lg p-6 mb-4 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Beneficiary {{ $index + 1 }}</h3>
                @if($index > 0)
                <button type="button" onclick="removeBeneficiary(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Remove
                </button>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="beneficiaries[{{ $index }}][name]" value="{{ $beneficiary['name'] ?? '' }}" class="form-input" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Relationship</label>
                    <input type="text" name="beneficiaries[{{ $index }}][relationship]" value="{{ $beneficiary['relationship'] ?? '' }}" class="form-input" placeholder="e.g., Spouse, Child, Parent">
                </div>
                <div class="form-group">
                    <label class="form-label">Allocation (%)</label>
                    <input type="number" name="beneficiaries[{{ $index }}][allocation]" value="{{ $beneficiary['allocation'] ?? '' }}" min="0" max="100" step="0.01" class="form-input" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Contact (Mobile/Email)</label>
                    <input type="text" name="beneficiaries[{{ $index }}][contact]" value="{{ $beneficiary['contact'] ?? '' }}" class="form-input" placeholder="Phone or email">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <button type="button" onclick="addBeneficiary()" class="mb-6 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
        + Add Another Beneficiary
    </button>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step6') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 8
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>

@push('scripts')
<script>
let beneficiaryCount = {{ count($beneficiaries) }};

function addBeneficiary() {
    const container = document.getElementById('beneficiaries-container');
    const newItem = document.createElement('div');
    newItem.className = 'beneficiary-item border-2 border-gray-200 rounded-lg p-6 mb-4 bg-gray-50';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Beneficiary ${beneficiaryCount + 1}</h3>
            <button type="button" onclick="removeBeneficiary(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][name]" class="form-input" placeholder="Full name">
            </div>
            <div class="form-group">
                <label class="form-label">Relationship</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][relationship]" class="form-input" placeholder="e.g., Spouse, Child, Parent">
            </div>
            <div class="form-group">
                <label class="form-label">Allocation (%)</label>
                <input type="number" name="beneficiaries[${beneficiaryCount}][allocation]" min="0" max="100" step="0.01" class="form-input" placeholder="0.00">
            </div>
            <div class="form-group">
                <label class="form-label">Contact (Mobile/Email)</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][contact]" class="form-input" placeholder="Phone or email">
            </div>
        </div>
    `;
    container.appendChild(newItem);
    beneficiaryCount++;
}

function removeBeneficiary(button) {
    button.closest('.beneficiary-item').remove();
}
</script>
@endpush
@endsection

