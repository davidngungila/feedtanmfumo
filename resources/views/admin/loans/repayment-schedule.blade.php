@extends('layouts.admin')

@section('page-title', 'Repayment Schedule')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Repayment Schedule</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View and manage loan repayment schedules</p>
            </div>
        </div>
    </div>

    <!-- Loan Selection -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Select Loan</h2>
        <form method="GET" action="{{ route('admin.loans.repayment-schedule') }}" class="flex gap-4">
            <select name="loan_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                <option value="">-- Select a loan --</option>
                @foreach($activeLoans as $activeLoan)
                    <option value="{{ $activeLoan->id }}" {{ $loan && $loan->id == $activeLoan->id ? 'selected' : '' }}>
                        {{ $activeLoan->loan_number }} - {{ $activeLoan->user->name }} ({{ number_format($activeLoan->total_amount, 0) }} TZS)
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                View Schedule
            </button>
        </form>
    </div>

    @if($loan)
    <!-- Loan Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <p class="text-xs text-gray-600 mb-1">Loan Number</p>
                <p class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 mb-1">Member</p>
                <p class="text-sm font-semibold text-gray-900">{{ $loan->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 mb-1">Total Amount</p>
                <p class="text-sm font-semibold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 mb-1">Monthly Payment</p>
                <p class="text-sm font-semibold text-green-600">{{ number_format($loan->total_amount / $loan->term_months, 0) }} TZS</p>
            </div>
        </div>
    </div>

    <!-- Repayment Schedule Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Repayment Schedule</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Installment #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Remaining</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedule as $installment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $installment['installment'] }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $installment['due_date']->format('M d, Y') }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($installment['amount'], 0) }} TZS</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $installment['status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                    ($installment['status'] === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                                }}">
                                    {{ strtoupper($installment['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @if($installment['due_date']->isPast())
                                    <span class="text-red-600">Overdue by {{ $installment['due_date']->diffForHumans() }}</span>
                                @else
                                    {{ $installment['due_date']->diffForHumans() }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">No schedule available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <p class="text-gray-500 font-medium">Select a loan to view its repayment schedule</p>
    </div>
    @endif
</div>
@endsection

