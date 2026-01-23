@extends('layouts.admin')

@section('page-title', 'Create New Investment')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Create New Investment</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Register a new investment plan for a member</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.investments.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.investments.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                    <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Member</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Investment Plan *</label>
                    <select name="plan_type" id="plan_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="4_year">4-Year Investment Plan</option>
                        <option value="6_year">6-Year Investment Plan</option>
                    </select>
                    @error('plan_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Principal Amount (TZS) *</label>
                    <input type="number" name="principal_amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('principal_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%) *</label>
                    <input type="number" name="interest_rate" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('interest_rate')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Maturity Date</label>
                    <input type="text" id="maturity_date_preview" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">Calculated automatically based on plan type</p>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Create Investment
                </button>
                <a href="{{ route('admin.investments.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('plan_type').addEventListener('change', updateMaturityDate);
    document.querySelector('input[name="start_date"]').addEventListener('change', updateMaturityDate);
    
    function updateMaturityDate() {
        const planType = document.getElementById('plan_type').value;
        const startDate = document.querySelector('input[name="start_date"]').value;
        if (startDate) {
            const date = new Date(startDate);
            const years = planType === '4_year' ? 4 : 6;
            date.setFullYear(date.getFullYear() + years);
            document.getElementById('maturity_date_preview').value = date.toISOString().split('T')[0];
        }
    }
</script>
@endsection

