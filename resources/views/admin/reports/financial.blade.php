@extends('layouts.admin')

@section('page-title', 'Financial Reports')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Financial Reports</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive financial overview and analysis</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.reports.financial.pdf') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
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
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Outstanding Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_loans'], 0) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Active loan balance</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Savings</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_savings'], 0) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">All account balances</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Investments</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['total_investments'], 0) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Investment portfolio</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Welfare Fund</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['total_welfare_fund'], 0) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Net welfare balance</p>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Principal</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['total_principal'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Paid</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_paid'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($stats['total_revenue'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Members</p>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ number_format($stats['total_members'], 0) }}</p>
        </div>
    </div>

    <!-- Loan Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-[#015425]">Loan Statistics by Status</h3>
            <span class="text-sm text-gray-500">As of {{ now()->format('M d, Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalLoanAmount = $loanStats->sum('total');
                    @endphp
                    @forelse($loanStats as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full {{ 
                                    $stat->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($stat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($stat->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($stat->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ number_format($stat->count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($stat->total, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-[#015425] h-2 rounded-full" style="width: {{ $totalLoanAmount > 0 ? ($stat->total / $totalLoanAmount) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $totalLoanAmount > 0 ? number_format(($stat->total / $totalLoanAmount) * 100, 1) : 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No loan data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Savings Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-[#015425]">Savings Statistics by Account Type</h3>
            <span class="text-sm text-gray-500">As of {{ now()->format('M d, Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalSavingsBalance = $savingsStats->sum('total');
                    @endphp
                    @forelse($savingsStats as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $stat->account_type)) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ number_format($stat->count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($stat->total, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalSavingsBalance > 0 ? ($stat->total / $totalSavingsBalance) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $totalSavingsBalance > 0 ? number_format(($stat->total / $totalSavingsBalance) * 100, 1) : 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No savings data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Loans Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Loan Applications ({{ date('Y') }})</h3>
            <div class="space-y-4">
                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    $maxLoanCount = $monthlyLoans->max('count') ?? 1;
                @endphp
                @foreach($months as $index => $month)
                    @php
                        $monthData = $monthlyLoans->firstWhere('month', $index + 1);
                        $count = $monthData->count ?? 0;
                        $total = $monthData->total ?? 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }} loans ({{ number_format($total / 1000, 1) }}K TZS)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-[#015425] h-3 rounded-full" style="width: {{ $maxLoanCount > 0 ? ($count / $maxLoanCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Savings Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Savings Deposits ({{ date('Y') }})</h3>
            <div class="space-y-4">
                @php
                    $maxSavingsTotal = $monthlySavings->max('total') ?? 1;
                @endphp
                @foreach($months as $index => $month)
                    @php
                        $monthData = $monthlySavings->firstWhere('month', $index + 1);
                        $total = $monthData->total ?? 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($total / 1000, 1) }}K TZS</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full" style="width: {{ $maxSavingsTotal > 0 ? ($total / $maxSavingsTotal) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h3 class="text-xl font-bold mb-4">Financial Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-white text-opacity-80 mb-1">Total Assets</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_savings'] + $stats['total_investments'] + $stats['total_welfare_fund'], 0) }} TZS</p>
            </div>
            <div>
                <p class="text-sm text-white text-opacity-80 mb-1">Total Liabilities</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_loans'], 0) }} TZS</p>
            </div>
            <div>
                <p class="text-sm text-white text-opacity-80 mb-1">Net Position</p>
                <p class="text-2xl font-bold">{{ number_format(($stats['total_savings'] + $stats['total_investments'] + $stats['total_welfare_fund']) - $stats['total_loans'], 0) }} TZS</p>
            </div>
        </div>
    </div>
</div>
@endsection
