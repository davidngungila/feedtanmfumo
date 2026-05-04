@extends('layouts.admin')

@section('page-title', 'Loan Repayment Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Repayment Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Detailed analysis of loan repayment patterns, schedules, and collection efficiency</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.reports.loans.repayment.pdf') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
                <a href="{{ route('admin.reports.loans') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Loan Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Repayment Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-[#015425] bg-opacity-10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Repayments</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($repaymentStats['total_repayments']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Amount Paid</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($repaymentStats['total_amount_paid'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Avg Repayment</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($repaymentStats['avg_repayment_amount'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Loans with Repayments</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($repaymentStats['loans_with_repayments']) }}</p>
        </div>
    </div>

    <!-- Repayment Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">On-Time Repayments</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($repaymentStats['on_time_repayments']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Payments made on or before due date</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Late Repayments</p>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ number_format($repaymentStats['late_repayments']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Payments made after due date</p>
        </div>
    </div>

    <!-- Monthly Repayment Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Repayment Trend ({{ date('Y') }})</h3>
        <div class="space-y-4">
            @php
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $maxMonthlyRepayments = $monthlyRepayments->max('count') ?? 1;
            @endphp
            @foreach($months as $index => $month)
                @php
                    $monthData = $monthlyRepayments->firstWhere('month', $index + 1);
                    $count = $monthData->count ?? 0;
                    $total = $monthData->total ?? 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $count }} repayments ({{ number_format($total / 1000, 1) }}K TZS)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $maxMonthlyRepayments > 0 ? ($count / $maxMonthlyRepayments) * 100 : 0 }}%"></div>
                    </div>
                    @if($count > 0)
                        <div class="mt-1 text-xs text-gray-500">
                            Average: {{ number_format($total / $count, 0) }} TZS per repayment
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Repayment Summary Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Repayment Summary</h3>
        <div class="space-y-4">
            @php
                $totalRepayments = $repaymentStats['total_repayments'];
                $onTimeRate = $totalRepayments > 0 ? ($repaymentStats['on_time_repayments'] / $totalRepayments) * 100 : 0;
                $lateRate = $totalRepayments > 0 ? ($repaymentStats['late_repayments'] / $totalRepayments) * 100 : 0;
            @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs rounded-full mr-3 bg-green-100 text-green-800">On-Time</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($repaymentStats['on_time_repayments']) }} payments</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($onTimeRate, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: {{ $onTimeRate }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs rounded-full mr-3 bg-orange-100 text-orange-800">Late</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($repaymentStats['late_repayments']) }} payments</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($lateRate, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-orange-600 h-3 rounded-full" style="width: {{ $lateRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Repayment Details Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Repayment Transaction Details</h3>
            <span class="text-sm text-gray-500">Showing {{ $repayments->firstItem() ?? 0 }}-{{ $repayments->lastItem() ?? 0 }} of {{ $repayments->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($repayments as $repayment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-[#015425]">#{{ str_pad($repayment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $repayment->loan->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $repayment->loan->loan_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-green-600">{{ number_format($repayment->amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($repayment->payment_method ?? 'Cash') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $repayment->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No repayment transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($repayments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $repayments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
