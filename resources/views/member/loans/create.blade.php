@extends('layouts.member')

@section('page-title', 'Apply for Loan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-4 sm:mb-6">Apply for Loan</h2>
        
        <form action="{{ route('member.loans.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount (TZS) *</label>
                    <input type="number" name="principal_amount" step="0.01" min="1000" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Minimum amount: 1,000 TZS</p>
                    @error('principal_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Purpose *</label>
                    <textarea name="purpose" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Please describe the purpose of this loan</p>
                    @error('purpose')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Term (Months) *</label>
                        <select name="term_months" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="">Select term</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12">12 Months</option>
                            <option value="18">18 Months</option>
                            <option value="24">24 Months</option>
                            <option value="36">36 Months</option>
                        </select>
                        @error('term_months')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Frequency *</label>
                        <select name="payment_frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="bi-weekly">Bi-weekly</option>
                        </select>
                        @error('payment_frequency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Submit Application
                </button>
                <a href="{{ route('member.loans.index') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

