@extends('layouts.admin')

@section('page-title', 'Loan Disbursement')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Disbursement</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Process and disburse approved loans to members</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.loans.approval-workflow') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Approval Workflow
                </a>
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Approved Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ number_format($stats['total']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Amount</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['total_amount'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Ready for Disbursement</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['ready_for_disbursement']) }}</p>
        </div>
    </div>

    <!-- Information Box -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-900 mb-2">Disbursement Process</h3>
                <p class="text-sm text-green-800 mb-2">Before disbursing a loan, ensure:</p>
                <ul class="text-sm text-green-800 list-disc list-inside space-y-1">
                    <li>Loan has been fully approved by authorized personnel</li>
                    <li>All required documentation is complete and verified</li>
                    <li>Member account details are correct</li>
                    <li>Disbursement date is recorded accurately</li>
                    <li>Loan status is updated to "Active" upon disbursement</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-[#015425]">Approved Loans Ready for Disbursement</h2>
            <input type="text" id="search-input" placeholder="Search loans..." 
                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] text-sm">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approval Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terms</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @if($loan->approval_date)
                                    {{ $loan->approval_date->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $loan->interest_rate }}% / {{ $loan->term_months }}m</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:text-[#013019] transition" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.loans.edit', $loan) }}" class="text-green-600 hover:text-green-800 transition" title="Disburse">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">No loans ready for disbursement</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

