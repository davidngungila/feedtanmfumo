@extends('layouts.admin')

@section('page-title', 'Loan Portfolio Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Portfolio Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive analysis of loan portfolio composition and risk exposure</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.reports.loans.portfolio.pdf') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
                <a href="{{ route('admin.reports.loans') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Loan Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Portfolio Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-[#015425] bg-opacity-10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($portfolioStats['total_loans']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Portfolio Value</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($portfolioStats['portfolio_value'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($portfolioStats['active_loans']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Risk Exposure</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($portfolioStats['risk_exposure'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Portfolio Analysis -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Completed Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($portfolioStats['completed_loans']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600">{{ number_format($portfolioStats['overdue_loans']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Principal</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($portfolioStats['total_principal'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Remaining</p>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ number_format($portfolioStats['total_remaining'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Portfolio Composition Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Portfolio Composition</h3>
        <div class="space-y-4">
            @php
                $maxCount = max($portfolioStats['active_loans'], $portfolioStats['completed_loans'], $portfolioStats['overdue_loans']) ?: 1;
            @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs rounded-full mr-3 bg-green-100 text-green-800">Active</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($portfolioStats['active_loans']) }} loans</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($portfolioStats['total_loans'] > 0 ? ($portfolioStats['active_loans'] / $portfolioStats['total_loans']) * 100 : 0, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: {{ ($portfolioStats['active_loans'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs rounded-full mr-3 bg-blue-100 text-blue-800">Completed</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($portfolioStats['completed_loans']) }} loans</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($portfolioStats['total_loans'] > 0 ? ($portfolioStats['completed_loans'] / $portfolioStats['total_loans']) * 100 : 0, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($portfolioStats['completed_loans'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs rounded-full mr-3 bg-red-100 text-red-800">Overdue</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($portfolioStats['overdue_loans']) }} loans</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($portfolioStats['total_loans'] > 0 ? ($portfolioStats['overdue_loans'] / $portfolioStats['total_loans']) * 100 : 0, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-600 h-3 rounded-full" style="width: {{ ($portfolioStats['overdue_loans'] / $maxCount) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans Portfolio Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Loan Portfolio Details</h3>
            <span class="text-sm text-gray-500">Showing {{ $loans->firstItem() ?? 0 }}-{{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maturity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-[#015425]">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $loan->user->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-semibold">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $progress = $loan->principal_amount > 0 ? ($loan->paid_amount / $loan->principal_amount) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, $progress) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ number_format($progress, 1) }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($loan->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $loan->maturity_date ? $loan->maturity_date->format('M d, Y') : 'N/A' }}
                                @if($loan->maturity_date && $loan->maturity_date < now() && $loan->status === 'active')
                                    <span class="text-red-600 font-semibold"> (Overdue)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No loans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
