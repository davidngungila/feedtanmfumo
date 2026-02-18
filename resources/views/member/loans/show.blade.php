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
            <!-- Loan Documents -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Loan Documents
                </h3>
                <div class="space-y-3">
                    @if($loan->schedule_path)
                    <a href="{{ Storage::url($loan->schedule_path) }}" target="_blank" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-[#015425] transition group">
                        <div class="w-8 h-8 rounded bg-red-100 flex items-center justify-center mr-3 group-hover:bg-[#015425] transition">
                            <svg class="w-4 h-4 text-red-600 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l3 3a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Repayment Schedule</p>
                            <p class="text-[10px] text-gray-500">PDF Document</p>
                        </div>
                    </a>
                    @endif

                    @if($loan->agreement_path)
                    <a href="{{ Storage::url($loan->agreement_path) }}" target="_blank" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-[#015425] transition group">
                        <div class="w-8 h-8 rounded bg-blue-100 flex items-center justify-center mr-3 group-hover:bg-[#015425] transition">
                            <svg class="w-4 h-4 text-blue-600 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l3 3a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Loan Agreement</p>
                            <p class="text-[10px] text-gray-500">PDF Document</p>
                        </div>
                    </a>
                    @endif

                    @if(!$loan->schedule_path && !$loan->agreement_path)
                    <p class="text-xs text-gray-500 italic">No documents generated yet.</p>
                    @endif
                </div>
            </div>

            @if($loan->guarantor)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Mdhamini
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-[#015425] font-bold">
                        {{ substr($loan->guarantor->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $loan->guarantor->name }}</p>
                        <p class="text-xs text-gray-500">Code: {{ $loan->guarantor->membership_code }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($loan->approver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4">Muidhinishaji</h3>
                <p class="text-sm font-semibold text-gray-800">{{ $loan->approver->name }}</p>
            </div>
            @endif

            @if($loan->status === 'active')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-2">Kikumbusho cha Malipo</h3>
                <p class="text-sm text-blue-700">Bado una salio la TZS {{ number_format($loan->remaining_amount, 0) }} ambalo unapaswa kulirejesha.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

