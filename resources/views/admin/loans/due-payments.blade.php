@extends('layouts.admin')

@section('page-title', 'Due Payments')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Due Payments</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Track loans with payments due on or before selected date</p>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.loans.due-payments') }}" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                <input type="date" name="date" value="{{ $stats['date']->format('Y-m-d') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Loans Due</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($stats['total_due']) }}</p>
            <p class="text-xs text-gray-500 mt-1">As of {{ $stats['date']->format('M d, Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Amount Due</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_amount_due'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Loans with Due Payments</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monthly Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        @php
                            $monthlyPayment = $loan->term_months > 0 ? $loan->total_amount / $loan->term_months : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ number_format($loan->total_amount, 0) }} TZS</td>
                            <td class="px-4 py-4 text-sm font-semibold text-orange-600">{{ number_format($monthlyPayment, 0) }} TZS</td>
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
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $loan->maturity_date && $loan->maturity_date->isPast() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                                }}">
                                    {{ $loan->maturity_date && $loan->maturity_date->isPast() ? 'OVERDUE' : 'DUE SOON' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:text-[#013019] transition">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">No loans with due payments found</td>
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

