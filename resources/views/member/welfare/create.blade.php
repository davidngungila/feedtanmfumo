@extends('layouts.member')

@section('page-title', 'New Welfare Record')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-4 sm:mb-6">New Welfare Record</h2>
        
        <form action="{{ route('member.welfare.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" id="welfare_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="contribution">Contribution</option>
                        <option value="benefit">Benefit</option>
                    </select>
                    @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="benefit_type_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Type *</label>
                    <select name="benefit_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="medical">Medical Support</option>
                        <option value="funeral">Funeral Support</option>
                        <option value="educational">Educational Support</option>
                        <option value="other">Other Support</option>
                    </select>
                    @error('benefit_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                    <input type="number" name="amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Submit
                </button>
                <a href="{{ route('member.welfare.index') }}" class="w-full sm:w-auto px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('welfare_type').addEventListener('change', function() {
        const benefitField = document.getElementById('benefit_type_field');
        if (this.value === 'benefit') {
            benefitField.style.display = 'block';
            benefitField.querySelector('select').required = true;
        } else {
            benefitField.style.display = 'none';
            benefitField.querySelector('select').required = false;
        }
    });
</script>
@endsection

