@extends('layouts.admin')

@section('page-title', 'Account Statements')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Account Statements</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Generate and view account statements for savings accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Statement Selection Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Select Account & Period</h2>
        <form action="{{ route('admin.savings.statements') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Account</label>
                <select name="account_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">-- Select Account --</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->account_number }} - {{ $account->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date', now()->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Generate Statement
                </button>
            </div>
        </form>
    </div>

    @if($selectedAccount)
    <!-- Account Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Account Number</p>
            <p class="text-lg font-bold text-[#015425]">{{ $selectedAccount->account_number }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Account Type</p>
            <p class="text-lg font-bold text-gray-900">{{ $selectedAccount->account_type_name }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Current Balance</p>
            <p class="text-lg font-bold text-green-600">{{ number_format($selectedAccount->balance, 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Interest Rate</p>
            <p class="text-lg font-bold text-blue-600">{{ number_format($selectedAccount->interest_rate, 2) }}%</p>
        </div>
    </div>

    <!-- Account Holder Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Holder Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Name:</p>
                <p class="text-base font-semibold text-gray-900">{{ $selectedAccount->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email:</p>
                <p class="text-base font-semibold text-gray-900">{{ $selectedAccount->user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Account Status:</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $selectedAccount->status === 'active' ? 'bg-green-100 text-green-800' : ($selectedAccount->status === 'frozen' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ strtoupper($selectedAccount->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Opening Date:</p>
                <p class="text-base font-semibold text-gray-900">{{ $selectedAccount->opening_date->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-[#015425]">Transaction History</h2>
            <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium text-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Statement
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $runningBalance = $selectedAccount->balance;
                    @endphp
                    @forelse($transactions as $transaction)
                        @php
                            $isCredit = in_array($transaction->transaction_type, ['savings_deposit']);
                            $runningBalance = $isCredit ? $runningBalance - $transaction->amount : $runningBalance + $transaction->amount;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $isCredit = in_array($transaction->transaction_type, ['savings_deposit']);
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $isCredit ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                }}">
                                    {{ str_replace('_', ' ', strtoupper($transaction->transaction_type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $transaction->description ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">
                                @if(!$isCredit)
                                    {{ number_format($transaction->amount, 0) }} TZS
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-green-600">
                                @if($isCredit)
                                    {{ number_format($transaction->amount, 0) }} TZS
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($runningBalance, 0) }} TZS</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">No transactions found for the selected period</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection

