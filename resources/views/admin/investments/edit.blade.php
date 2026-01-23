@extends('layouts.admin')

@section('page-title', 'Edit Investment')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Investment</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Update investment details and status</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.investments.show', $investment) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    View Details
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.investments.update', $investment) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Investment Number</label>
                        <input type="text" value="{{ $investment->investment_number }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan Type</label>
                        <input type="text" value="{{ $investment->plan_type_name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Principal Amount</label>
                        <input type="text" value="{{ number_format($investment->principal_amount, 0) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Return</label>
                        <input type="text" value="{{ number_format($investment->expected_return, 0) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="active" {{ $investment->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="matured" {{ $investment->status === 'matured' ? 'selected' : '' }}>Matured</option>
                            <option value="disbursed" {{ $investment->status === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                            <option value="cancelled" {{ $investment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div id="profit_share_field" style="{{ $investment->status === 'matured' ? '' : 'display: none;' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profit Share (TZS)</label>
                        <input type="number" name="profit_share" step="0.01" min="0" value="{{ $investment->profit_share }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>

                    <div id="disbursement_date_field" style="{{ $investment->status === 'disbursed' ? '' : 'display: none;' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Disbursement Date</label>
                        <input type="date" name="disbursement_date" value="{{ $investment->disbursement_date ? $investment->disbursement_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ $investment->notes }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Update Investment
                </button>
                <a href="{{ route('admin.investments.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('status').addEventListener('change', function() {
        const profitField = document.getElementById('profit_share_field');
        const disbursementField = document.getElementById('disbursement_date_field');
        
        if (this.value === 'matured' || this.value === 'disbursed') {
            profitField.style.display = 'block';
        } else {
            profitField.style.display = 'none';
        }
        
        if (this.value === 'disbursed') {
            disbursementField.style.display = 'block';
            disbursementField.querySelector('input').required = true;
        } else {
            disbursementField.style.display = 'none';
            disbursementField.querySelector('input').required = false;
        }
    });
</script>
@endsection

