@extends('layouts.member')

@section('page-title', 'Loan Details')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">Loan Details</h1>
        <a href="{{ route('member.loans.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center text-sm sm:text-base">
            Back to Loans
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-3 sm:mb-4">Loan Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Loan Number</p>
                        <p class="text-lg font-semibold">{{ $loan->loan_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ 
                            $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                            ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                        }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Principal Amount</p>
                        <p class="text-2xl font-bold text-[#015425]">{{ number_format($loan->principal_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Interest Rate</p>
                        <p class="text-lg font-semibold">{{ $loan->interest_rate }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Term</p>
                        <p class="text-lg font-semibold">{{ $loan->term_months }} Months</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Paid Amount</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($loan->paid_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Remaining</p>
                        <p class="text-xl font-bold text-red-600">{{ number_format($loan->remaining_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Application Date</p>
                        <p class="text-lg font-semibold">{{ $loan->application_date->format('M d, Y') }}</p>
                    </div>
                    @if($loan->approval_date)
                    <div>
                        <p class="text-sm text-gray-600">Approval Date</p>
                        <p class="text-lg font-semibold">{{ $loan->approval_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($loan->maturity_date)
                    <div>
                        <p class="text-sm text-gray-600">Maturity Date</p>
                        <p class="text-lg font-semibold">{{ $loan->maturity_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
                
                @if($loan->purpose)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Purpose</p>
                    <p class="text-gray-900">{{ $loan->purpose }}</p>
                </div>
                @endif
            </div>

            <!-- Transactions -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-3 sm:mb-4">Payment History</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold">{{ number_format($transaction->amount, 0) }} TZS</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ 
                                            $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                        }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">No payments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @if($loan->approver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4">Approved By</h3>
                <p class="font-semibold">{{ $loan->approver->name }}</p>
            </div>
            @endif

            @if($loan->status === 'active')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-2">Payment Reminder</h3>
                <p class="text-sm text-blue-700">You have an outstanding balance of {{ number_format($loan->remaining_amount, 0) }} TZS</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

