@extends('layouts.admin')

@section('page-title', 'Total Savings Balance')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Total Savings Balance</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Overview of total savings balances by account type</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Report
                </button>
                <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Balance</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_balance'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['active_accounts']) }}</p>
            <p class="text-xs text-gray-500 mt-1">of {{ number_format($stats['total_accounts']) }} total</p>
        </div>
    </div>

    <!-- Account Type Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Emergency Savings</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['emergency'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">RDA Accounts</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['rda'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Flex Accounts</p>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['flex'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Business Savings</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['business'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
    </div>

    <!-- Growth Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Savings Growth Trend (Last 6 Months)</h2>
        <div class="space-y-4">
            @foreach($monthlyGrowth as $month)
                @php
                    $maxBalance = max(array_column($monthlyGrowth, 'balance'));
                    $percentage = $maxBalance > 0 ? min(100, ($month['balance'] / $maxBalance) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ $month['month'] }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($month['balance'] / 1000, 1) }}K TZS</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">All Savings Accounts</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $account->account_number }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($account->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $account->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $account->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $account->account_type_name }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($account->balance, 0) }} TZS</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $account->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($account->status === 'frozen' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ strtoupper($account->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.savings.show', ['saving' => $account->id]) }}" class="text-[#015425] hover:text-[#013019] transition" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">No savings accounts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($accounts->hasPages())
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

