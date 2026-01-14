@extends('layouts.admin')

@section('page-title', 'Withdrawal Requests')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Withdrawal Requests</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Process and manage withdrawal requests from savings accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.savings.deposits') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Deposits
                </a>
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
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Today's Withdrawals</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_withdrawals_today'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">This Month</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($stats['total_withdrawals_month'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Requests</p>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ number_format($stats['pending_requests']) }}</p>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Process New Withdrawal</h2>
        <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Account</label>
                <select name="account_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">-- Select Account --</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_number }} - {{ $account->user->name }} ({{ number_format($account->balance, 0) }} TZS)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount</label>
                <input type="number" name="amount" step="0.01" min="0" placeholder="Enter amount" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Reason</label>
                <textarea name="reason" rows="3" placeholder="Enter reason for withdrawal" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Process Withdrawal
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Withdrawals Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Recent Withdrawals</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $withdrawal->related_id ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $withdrawal->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">{{ number_format($withdrawal->amount ?? 0, 0) }} TZS</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">No withdrawal transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

