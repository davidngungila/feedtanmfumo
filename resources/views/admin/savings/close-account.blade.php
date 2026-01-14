@extends('layouts.admin')

@section('page-title', 'Close Account')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Close Account</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Close savings accounts and process final settlements</p>
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
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_active']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Closed Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-600">{{ number_format($stats['total_closed']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Closures</p>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ number_format($stats['pending_closures']) }}</p>
        </div>
    </div>

    <!-- Information Box -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-red-900 mb-2">Account Closure Requirements</h3>
                <p class="text-sm text-red-800 mb-2">Before closing an account, ensure:</p>
                <ul class="text-sm text-red-800 list-disc list-inside space-y-1">
                    <li>Account balance is zero or all funds have been withdrawn</li>
                    <li>All pending transactions are completed</li>
                    <li>Member has been notified of account closure</li>
                    <li>Account closure form is filled and signed by member</li>
                    <li>All required documentation is in order</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-[#015425]">Active Accounts Available for Closure</h2>
            <input type="text" id="search-input" placeholder="Search accounts..." 
                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] text-sm">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opening Date</th>
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
                            <td class="px-4 py-4 text-sm font-semibold {{ $account->balance > 0 ? 'text-green-600' : 'text-gray-600' }}">
                                {{ number_format($account->balance, 0) }} TZS
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $account->opening_date->format('M d, Y') }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.savings.show', ['saving' => $account->id]) }}" class="text-[#015425] hover:text-[#013019] transition" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button class="text-red-600 hover:text-red-800 transition {{ $account->balance > 0 ? 'opacity-50 cursor-not-allowed' : '' }}" title="Close Account" {{ $account->balance > 0 ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">No active accounts found</td>
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

