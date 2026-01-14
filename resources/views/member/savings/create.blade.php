@extends('layouts.member')

@section('page-title', 'Open Savings Account')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-4 sm:mb-6">Open New Savings Account</h2>
        
        <form action="{{ route('member.savings.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Type *</label>
                    <select name="account_type" id="account_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="emergency">Emergency Savings</option>
                        <option value="rda">Recurrent Deposit Account (RDA)</option>
                        <option value="flex">Flex Account</option>
                        <option value="business">Business Savings</option>
                    </select>
                    @error('account_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Opening Date *</label>
                    <input type="date" name="opening_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('opening_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="maturity_date_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maturity Date *</label>
                    <input type="date" name="maturity_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('maturity_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Open Account
                </button>
                <a href="{{ route('member.savings.index') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('account_type').addEventListener('change', function() {
        const maturityField = document.getElementById('maturity_date_field');
        if (this.value === 'rda') {
            maturityField.style.display = 'block';
            maturityField.querySelector('input').required = true;
        } else {
            maturityField.style.display = 'none';
            maturityField.querySelector('input').required = false;
        }
    });
</script>
@endsection

