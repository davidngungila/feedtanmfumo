@extends('layouts.admin')

@section('page-title', 'Welfare Fund Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Fund Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Monitor and manage welfare fund balance, contributions, and disbursements</p>
            </div>
            <a href="{{ route('admin.welfare.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Welfare
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.welfare.fund-management') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Fund Overview Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Contributions</p>
                    <p class="text-2xl font-bold text-green-600">TZS {{ number_format($stats['total_contributions'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Benefits</p>
                    <p class="text-2xl font-bold text-red-600">TZS {{ number_format($stats['total_benefits'], 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Net Balance</p>
                    <p class="text-2xl font-bold {{ $stats['net_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        TZS {{ number_format($stats['net_balance'], 2) }}
                    </p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Transactions</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_transactions']) }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Benefit Status Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pending Benefits</h3>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">{{ number_format($stats['pending_benefits'], 2) }} TZS</span>
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($stats['pending_benefits'], 2) }}</p>
            <p class="text-sm text-gray-600 mt-2">Awaiting approval</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Approved Benefits</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ number_format($stats['approved_benefits'], 2) }} TZS</span>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['approved_benefits'], 2) }}</p>
            <p class="text-sm text-gray-600 mt-2">Ready for disbursement</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Disbursed Benefits</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ number_format($stats['disbursed_benefits'], 2) }} TZS</span>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ number_format($stats['disbursed_benefits'], 2) }}</p>
            <p class="text-sm text-gray-600 mt-2">Already paid out</p>
        </div>
    </div>

    <!-- Monthly Trends -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Monthly Trends</h2>
        <div class="space-y-4">
            @foreach($monthlyContributions as $month)
                @php
                    $monthName = \Carbon\Carbon::create(null, $month->month, 1)->format('F');
                    $benefitMonth = $monthlyBenefits->firstWhere('month', $month->month);
                    $benefitAmount = $benefitMonth ? $benefitMonth->total : 0;
                    $net = $month->total - $benefitAmount;
                @endphp
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-900">{{ $monthName }}</h4>
                        <span class="text-sm font-semibold {{ $net >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Net: TZS {{ number_format($net, 2) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Contributions:</span>
                            <span class="font-semibold text-green-600 ml-2">TZS {{ number_format($month->total, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Benefits:</span>
                            <span class="font-semibold text-red-600 ml-2">TZS {{ number_format($benefitAmount, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['total_contributions'] > 0 ? ($month->total / $stats['total_contributions'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Benefit Type Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Benefit Type Breakdown</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($benefitBreakdown as $benefit)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ ucfirst($benefit->benefit_type ?? 'Other') }}</h4>
                    <p class="text-2xl font-bold text-blue-600">TZS {{ number_format($benefit->total, 2) }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $benefit->count }} claims</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-[#015425]">Recent Transactions</h2>
            <a href="{{ route('admin.welfare.index') }}" class="text-sm text-[#015425] hover:text-[#013019] font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->transaction_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $transaction->welfare_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'contribution' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $transaction->type === 'contribution' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'contribution' ? '+' : '-' }}TZS {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                    $transaction->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                    ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($transaction->status === 'disbursed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))
                                }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

