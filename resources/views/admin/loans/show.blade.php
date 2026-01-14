@extends('layouts.admin')

@section('page-title', 'Loan Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center mb-2">
                    <h1 class="text-2xl sm:text-3xl font-bold mr-3">Loan Details</h1>
                    <span class="px-3 py-1 text-sm rounded-full font-semibold {{ 
                        $loan->status === 'active' ? 'bg-green-500 text-white' : 
                        ($loan->status === 'pending' ? 'bg-yellow-500 text-white' : 
                        ($loan->status === 'overdue' ? 'bg-red-500 text-white' : 
                        ($loan->status === 'completed' ? 'bg-blue-500 text-white' : 
                        ($loan->status === 'rejected' ? 'bg-gray-500 text-white' : 
                        ($loan->status === 'approved' ? 'bg-indigo-500 text-white' : 'bg-gray-500 text-white')))))
                    }}">
                        {{ strtoupper($loan->status) }}
                    </span>
                </div>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Loan Number: <strong>{{ $loan->loan_number }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.loans.edit', $loan) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Loan
                    </div>
                </a>
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Financial Overview -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#015425]">Financial Overview</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <p class="text-xs text-blue-700 font-medium mb-1">Principal Amount</p>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($loan->principal_amount, 0) }}</p>
                        <p class="text-xs text-blue-600 mt-1">TZS</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                        <p class="text-xs text-orange-700 font-medium mb-1">Total Interest</p>
                        <p class="text-2xl font-bold text-orange-900">{{ number_format($loan->total_amount - $loan->principal_amount, 0) }}</p>
                        <p class="text-xs text-orange-600 mt-1">TZS</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <p class="text-xs text-green-700 font-medium mb-1">Paid Amount</p>
                        <p class="text-2xl font-bold text-green-900">{{ number_format($loan->paid_amount, 0) }}</p>
                        <p class="text-xs text-green-600 mt-1">TZS</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <p class="text-xs text-red-700 font-medium mb-1">Remaining</p>
                        <p class="text-2xl font-bold text-red-900">{{ number_format($loan->remaining_amount, 0) }}</p>
                        <p class="text-xs text-red-600 mt-1">TZS</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @if($loan->total_amount > 0)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Repayment Progress</span>
                        <span class="text-sm font-bold text-[#015425]">{{ round(($loan->paid_amount / $loan->total_amount) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] h-4 rounded-full transition-all duration-500" 
                             style="width: {{ min(($loan->paid_amount / $loan->total_amount) * 100, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>{{ number_format($loan->paid_amount, 0) }} TZS paid</span>
                        <span>{{ number_format($loan->total_amount, 0) }} TZS total</span>
                    </div>
                </div>
                @endif

                <!-- Loan Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Total Loan Amount</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Interest Rate</p>
                        <p class="text-xl font-bold text-[#015425]">{{ $loan->interest_rate }}% per annum</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Loan Term</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $loan->term_months }} Months</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Payment Frequency</p>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst(str_replace('-', ' ', $loan->payment_frequency)) }}</p>
                    </div>
                    @if($loan->total_amount > 0 && $loan->term_months > 0)
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Estimated Monthly Payment</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($loan->total_amount / $loan->term_months, 0) }} TZS</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Application Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $loan->application_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#015425]">Loan Timeline</h2>
                </div>

                <div class="space-y-4">
                    <!-- Application -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-900">Loan Application Submitted</p>
                            <p class="text-xs text-gray-500">{{ $loan->application_date->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Approval -->
                    @if($loan->approval_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-900">Loan Approved</p>
                            <p class="text-xs text-gray-500">{{ $loan->approval_date->format('M d, Y h:i A') }}</p>
                            @if($loan->approver)
                            <p class="text-xs text-gray-600 mt-1">By: {{ $loan->approver->name }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Disbursement -->
                    @if($loan->disbursement_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-900">Loan Disbursed</p>
                            <p class="text-xs text-gray-500">{{ $loan->disbursement_date->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-600 mt-1">Amount: {{ number_format($loan->principal_amount, 0) }} TZS</p>
                        </div>
                    </div>
                    @endif

                    <!-- Maturity -->
                    @if($loan->maturity_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 {{ $loan->maturity_date->isPast() ? 'bg-red-100' : 'bg-yellow-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $loan->maturity_date->isPast() ? 'text-red-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-900">Maturity Date</p>
                            <p class="text-xs text-gray-500">{{ $loan->maturity_date->format('M d, Y') }}</p>
                            @if($loan->maturity_date->isPast())
                            <p class="text-xs text-red-600 mt-1 font-medium">Overdue by {{ $loan->maturity_date->diffForHumans() }}</p>
                            @else
                            <p class="text-xs text-gray-600 mt-1">Due in {{ $loan->maturity_date->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Purpose & Notes -->
            @if($loan->purpose || $loan->rejection_reason)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#015425]">Additional Information</h2>
                </div>

                @if($loan->purpose)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Loan Purpose</p>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-900">{{ $loan->purpose }}</p>
                    </div>
                </div>
                @endif

                @if($loan->rejection_reason)
                <div>
                    <p class="text-sm font-medium text-red-700 mb-2">Rejection Reason</p>
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <p class="text-red-900">{{ $loan->rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Payment History</h2>
                    </div>
                    <span class="text-sm text-gray-500">{{ $loan->transactions->count() }} transaction(s)</span>
                </div>

                @if($loan->transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($loan->transactions as $transaction)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type ?? 'payment')) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ number_format($transaction->amount ?? 0, 0) }} TZS
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                            ($transaction->status ?? 'completed') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                        }}">
                                            {{ ucfirst($transaction->status ?? 'completed') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900">Total Paid</td>
                                <td class="px-4 py-3 text-sm font-bold text-green-600">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">No payment transactions yet</p>
                    <p class="text-sm text-gray-400 mt-1">Payments will appear here once recorded</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Borrower Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#015425]">Borrower</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Name</p>
                        <p class="font-semibold text-gray-900">{{ $loan->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Email</p>
                        <p class="text-sm text-gray-700 break-all">{{ $loan->user->email }}</p>
                    </div>
                    @if($loan->user->phone)
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Phone</p>
                        <p class="text-sm text-gray-700">{{ $loan->user->phone }}</p>
                    </div>
                    @endif
                    <div class="pt-4 border-t">
                        <a href="{{ route('admin.users.show', $loan->user) }}" class="text-sm text-[#015425] hover:underline font-medium">
                            View Full Profile →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            @if($loan->approver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#015425]">Approved By</h3>
                </div>
                <div class="space-y-2">
                    <p class="font-semibold text-gray-900">{{ $loan->approver->name }}</p>
                    @if($loan->approval_date)
                    <p class="text-sm text-gray-600">{{ $loan->approval_date->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Days Since Application</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $loan->application_date->diffInDays(now()) }} days</span>
                    </div>
                    @if($loan->maturity_date)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Days Until Maturity</span>
                        <span class="text-sm font-semibold {{ $loan->maturity_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ abs($loan->maturity_date->diffInDays(now())) }} days
                        </span>
                    </div>
                    @endif
                    @if($loan->total_amount > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Repayment Rate</span>
                        <span class="text-sm font-semibold text-green-600">{{ round(($loan->paid_amount / $loan->total_amount) * 100, 1) }}%</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Outstanding Balance Alert -->
            @if($loan->status === 'active' || $loan->status === 'overdue')
            <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-red-800">Outstanding Balance</h3>
                </div>
                <p class="text-3xl font-bold text-red-900 mb-2">{{ number_format($loan->remaining_amount, 0) }} TZS</p>
                @if($loan->maturity_date)
                <p class="text-sm text-red-700">
                    @if($loan->maturity_date->isPast())
                        <span class="font-semibold">Overdue!</span> Matured on {{ $loan->maturity_date->format('M d, Y') }}
                    @else
                        Matures on {{ $loan->maturity_date->format('M d, Y') }}
                    @endif
                </p>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-[#015425] mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($loan->status === 'pending')
                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium">
                        Approve Loan
                    </button>
                    @endif
                    @if($loan->status === 'approved')
                    <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">
                        Disburse Loan
                    </button>
                    @endif
                    @if($loan->status === 'active')
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        Record Payment
                    </button>
                    @endif
                    <a href="{{ route('admin.loans.edit', $loan) }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center font-medium">
                        Edit Loan Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
