@extends('layouts.admin')

@section('page-title', 'Edit Loan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Loan Application</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Loan Number: <strong>{{ $loan->loan_number }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    View Details
                </a>
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
                </a>
            </div>
        </div>
    </div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.loans.update', $loan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Loan Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loan Number</label>
                        <input type="text" value="{{ $loan->loan_number }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Loan number cannot be changed</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                        <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="">Select Member</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Principal Amount (TZS)</label>
                        <input type="number" name="principal_amount" value="{{ old('principal_amount', $loan->principal_amount) }}" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('principal_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" value="{{ old('interest_rate', $loan->interest_rate) }}" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('interest_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Term (Months)</label>
                        <input type="number" name="term_months" value="{{ old('term_months', $loan->term_months) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('term_months')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Frequency</label>
                        <select name="payment_frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="monthly" {{ $loan->payment_frequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="bi-weekly" {{ $loan->payment_frequency === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly</option>
                            <option value="weekly" {{ $loan->payment_frequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                        @error('payment_frequency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Application Date</label>
                        <input type="date" name="application_date" value="{{ old('application_date', $loan->application_date?->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('application_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="pending" {{ $loan->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $loan->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="disbursed" {{ $loan->status === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                            <option value="active" {{ $loan->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ $loan->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="overdue" {{ $loan->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="defaulted" {{ $loan->status === 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                            <option value="rejected" {{ $loan->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dates Section -->
            <div class="mb-6 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Important Dates</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Approval Date</label>
                        <input type="date" name="approval_date" value="{{ old('approval_date', $loan->approval_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('approval_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Disbursement Date</label>
                        <input type="date" name="disbursement_date" value="{{ old('disbursement_date', $loan->disbursement_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('disbursement_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($loan->maturity_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maturity Date</label>
                        <input type="text" value="{{ $loan->maturity_date->format('M d, Y') }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Calculated automatically</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Financial Information (Read-only) -->
            <div class="mb-6 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                        <input type="text" value="{{ number_format($loan->total_amount, 2) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paid Amount</label>
                        <input type="text" value="{{ number_format($loan->paid_amount, 2) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remaining Amount</label>
                        <input type="text" value="{{ number_format($loan->remaining_amount, 2) }} TZS" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                    </div>
                </div>
            </div>

            <!-- Purpose and Rejection Reason -->
            <div class="mb-6 border-t pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                        <textarea name="purpose" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('purpose', $loan->purpose) }}</textarea>
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                        <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Required if status is 'Rejected'">{{ old('rejection_reason', $loan->rejection_reason) }}</textarea>
                        @error('rejection_reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Update Loan
                </button>
                <a href="{{ route('admin.loans.show', $loan) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
                <a href="{{ route('admin.loans.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

