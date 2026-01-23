@extends('layouts.admin')

@section('page-title', 'Create Social Welfare Record')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Create Welfare Record</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Add new contribution or benefit record</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.welfare.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.welfare.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                    <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Member</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" id="welfare_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="contribution" {{ old('type') === 'contribution' ? 'selected' : '' }}>Contribution</option>
                        <option value="benefit" {{ old('type') === 'benefit' ? 'selected' : '' }}>Benefit</option>
                    </select>
                    @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="benefit_type_field" style="display: {{ old('type') === 'benefit' ? 'block' : 'none' }};">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Type *</label>
                    <select name="benefit_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Benefit Type</option>
                        <option value="medical" {{ old('benefit_type') === 'medical' ? 'selected' : '' }}>Medical Support</option>
                        <option value="funeral" {{ old('benefit_type') === 'funeral' ? 'selected' : '' }}>Funeral Support</option>
                        <option value="educational" {{ old('benefit_type') === 'educational' ? 'selected' : '' }}>Educational Support</option>
                        <option value="other" {{ old('benefit_type') === 'other' ? 'selected' : '' }}>Other Support</option>
                    </select>
                    @error('benefit_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                    <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                    <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6" id="eligibility_field" style="display: {{ old('type') === 'benefit' ? 'block' : 'none' }};">
                <label class="block text-sm font-medium text-gray-700 mb-2">Eligibility Notes</label>
                <textarea name="eligibility_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('eligibility_notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Add any notes about the member's eligibility for this benefit</p>
                @error('eligibility_notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Create Record
                </button>
                <a href="{{ route('admin.welfare.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('welfare_type').addEventListener('change', function() {
        const benefitField = document.getElementById('benefit_type_field');
        const eligibilityField = document.getElementById('eligibility_field');
        const benefitSelect = benefitField.querySelector('select');
        
        if (this.value === 'benefit') {
            benefitField.style.display = 'block';
            benefitSelect.required = true;
            eligibilityField.style.display = 'block';
        } else {
            benefitField.style.display = 'none';
            benefitSelect.required = false;
            eligibilityField.style.display = 'none';
        }
    });
</script>
@endsection
