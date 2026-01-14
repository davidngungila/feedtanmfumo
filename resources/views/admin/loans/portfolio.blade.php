@extends('layouts.admin')

@section('page-title', 'Loan Portfolio')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Portfolio</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive overview of all active and overdue loans</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
                </a>
            </div>
        </div>
    </div>

    <!-- Portfolio Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ number_format($stats['total_loans']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Portfolio Value</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['total_portfolio_value'] / 1000, 1) }}K</p>
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
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Outstanding</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_outstanding'] / 1000, 1) }}K</p>
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
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Recovery Rate</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['recovery_rate'], 1) }}%</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['total_recovered'] / 1000, 1) }}K TZS recovered</p>
        </div>
    </div>

    <!-- Portfolio Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Portfolio Breakdown</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Active Loans</span>
                        <span class="text-sm font-bold text-blue-600">{{ number_format($portfolioByStatus['active'] / 1000, 1) }}K TZS</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" 
                             style="width: {{ $stats['total_outstanding'] > 0 ? ($portfolioByStatus['active'] / $stats['total_outstanding']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Overdue Loans</span>
                        <span class="text-sm font-bold text-red-600">{{ number_format($portfolioByStatus['overdue'] / 1000, 1) }}K TZS</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-500 h-3 rounded-full" 
                             style="width: {{ $stats['total_outstanding'] > 0 ? ($portfolioByStatus['overdue'] / $stats['total_outstanding']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.loans.active') }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-center font-medium">
                    View Active Loans
                </a>
                <a href="{{ route('admin.loans.overdue') }}" class="block w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-center font-medium">
                    View Overdue Loans
                </a>
                <a href="{{ route('admin.reports.loans') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-center font-medium">
                    Generate Report
                </a>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Portfolio Loans</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outstanding</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                }}">
                                    {{ strtoupper($loan->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</td>
                            <td class="px-4 py-4 text-sm text-green-600">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                            <td class="px-4 py-4 text-sm font-semibold text-red-600">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:text-[#013019] transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">No loans in portfolio</td>
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

