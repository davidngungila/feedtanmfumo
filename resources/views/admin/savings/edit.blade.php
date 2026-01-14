@extends('layouts.admin')

@section('page-title', 'Edit Savings Account')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Savings Account</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Update savings account details and status</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.savings.show', $saving) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    View Details
                </a>
            </div>
        </div>
    </div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.savings.update', $saving) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" value="{{ $saving->account_number }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                        <input type="text" value="{{ $saving->account_type_name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Balance</label>
                        <input type="text" value="{{ number_format($saving->balance, 0) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="active" {{ $saving->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="closed" {{ $saving->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="frozen" {{ $saving->status === 'frozen' ? 'selected' : '' }}>Frozen</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" step="0.01" min="0" max="100" value="{{ $saving->interest_rate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ $saving->notes }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Update Account
                </button>
                <a href="{{ route('admin.savings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

