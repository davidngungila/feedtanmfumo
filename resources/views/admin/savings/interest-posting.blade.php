@extends('layouts.admin')

@section('page-title', 'Interest Posting')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Interest Posting</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Calculate and post interest to savings accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Interest Payable</p>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ number_format($stats['total_interest_payable'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS (Monthly)</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Accounts with Interest</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['total_accounts']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Last Posting Date</p>
            <p class="text-lg font-bold text-green-600">
                @if($stats['last_posting_date'])
                    {{ $stats['last_posting_date']->format('M d, Y') }}
                @else
                    Never
                @endif
            </p>
        </div>
    </div>

    <!-- Information Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Interest Calculation Information</h3>
                <p class="text-sm text-blue-800 mb-2">Interest is calculated based on:</p>
                <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                    <li>Account balance at the end of each month</li>
                    <li>Individual account interest rates</li>
                    <li>Monthly interest rate = (Annual Rate / 12)</li>
                    <li>Interest is posted to account balance automatically</li>
                    <li>Only active accounts with interest rate > 0% are included</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Post Interest Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Post Interest for Accounts</h2>
        <form action="#" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interest Period</label>
                    <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Posting Date</label>
                    <input type="date" name="posting_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Calculate & Post Interest
                </button>
            </div>
        </form>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Accounts Eligible for Interest Posting</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interest Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Interest</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        @php
                            $monthlyInterest = ($account->balance * $account->interest_rate) / 100 / 12;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $account->account_number }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($account->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $account->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $account->account_type_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($account->balance, 0) }} TZS</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ number_format($account->interest_rate, 2) }}%</td>
                            <td class="px-4 py-4 text-sm font-semibold text-green-600">{{ number_format($monthlyInterest, 2) }} TZS</td>
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
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">No accounts eligible for interest posting</td>
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

