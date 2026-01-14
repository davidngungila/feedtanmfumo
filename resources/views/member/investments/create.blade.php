@extends('layouts.member')

@section('page-title', 'Start Investment')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-4 sm:mb-6">Start New Investment</h2>
        
        <form action="{{ route('member.investments.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
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
                    <input type="number" name="principal_amount" step="0.01" min="1000" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Minimum amount: 1,000 TZS</p>
                    @error('principal_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Start Investment
                </button>
                <a href="{{ route('member.investments.index') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

