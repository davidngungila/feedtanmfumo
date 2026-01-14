@extends('layouts.admin')

@section('page-title', 'Active Loans')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Active Loans</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Monitor and manage all currently active loan accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.loans.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    New Loan
                </a>
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Active Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Loan Amount</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['total_amount'] / 1000, 1) }}K</p>
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
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Paid</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['total_paid'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Outstanding Balance</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_remaining'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-[#015425]">Active Loan Accounts</h2>
            <div class="flex flex-wrap gap-3">
                <input type="text" id="search-input" placeholder="Search loans..." 
                    class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] text-sm">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maturity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $loan->application_date->format('M d, Y') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ number_format($loan->principal_amount, 0) }} TZS</p>
                                    <p class="text-xs text-gray-500">{{ $loan->interest_rate }}% / {{ $loan->term_months }}m</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @if($loan->total_amount > 0)
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-500 h-2 rounded-full" 
                                             style="width: {{ min(($loan->paid_amount / $loan->total_amount) * 100, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">{{ round(($loan->paid_amount / $loan->total_amount) * 100, 0) }}%</span>
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-red-600">{{ number_format($loan->remaining_amount, 0) }} TZS</p>
                            </td>
                            <td class="px-4 py-4">
                                @if($loan->maturity_date)
                                <p class="text-sm text-gray-900">{{ $loan->maturity_date->format('M d, Y') }}</p>
                                <p class="text-xs {{ $loan->maturity_date->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ $loan->maturity_date->isPast() ? 'Overdue' : $loan->maturity_date->diffForHumans() }}
                                </p>
                                @else
                                <span class="text-xs text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:text-[#013019] transition" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.loans.edit', $loan) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">No active loans found</td>
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

