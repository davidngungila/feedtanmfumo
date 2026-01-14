@extends('layouts.admin')

@section('page-title', 'Savings Reports')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Savings Reports</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Savings accounts and deposits analysis</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
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
                <div class="w-12 h-12 bg-[#015425] bg-opacity-10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total_accounts']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Balance</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_balance'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['active_accounts']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Avg Balance</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['avg_balance'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Transaction Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Deposits</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_deposits'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Withdrawals</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600">{{ number_format($stats['total_withdrawals'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Savings by Type -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Savings by Account Type</h3>
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
                        $totalByType = $stats['by_type']->sum('total');
                    @endphp
                    @forelse($stats['by_type'] as $type)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $type->account_type)) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ number_format($type->count) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($type->total, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalByType > 0 ? ($type->total / $totalByType) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $totalByType > 0 ? number_format(($type->total / $totalByType) * 100, 1) : 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Savings by Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Savings by Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($stats['by_status'] as $status)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">{{ ucfirst($status->status) }}</p>
                    <p class="text-2xl font-bold text-[#015425]">{{ number_format($status->count) }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ number_format($status->total, 0) }} TZS</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Monthly Deposits Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Savings Deposits ({{ date('Y') }})</h3>
        <div class="space-y-4">
            @php
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $maxDepositTotal = $monthlyDeposits->max('total') ?? 1;
            @endphp
            @foreach($months as $index => $month)
                @php
                    $monthData = $monthlyDeposits->firstWhere('month', $index + 1);
                    $total = $monthData->total ?? 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($total / 1000, 1) }}K TZS</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $maxDepositTotal > 0 ? ($total / $maxDepositTotal) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">All Savings Accounts</h3>
            <span class="text-sm text-gray-500">Showing {{ $accounts->firstItem() ?? 0 }}-{{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opened</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-[#015425]">{{ $account->account_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $account->user->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $account->account_type_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-green-600">{{ number_format($account->balance, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ number_format($account->interest_rate, 2) }}%</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $account->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($account->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')
                                }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $account->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No savings accounts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($accounts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
