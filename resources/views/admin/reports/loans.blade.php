@extends('layouts.admin')

@section('page-title', 'Loan Reports')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Reports</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Detailed loan portfolio analysis and statistics</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-[#015425] bg-opacity-10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Amount</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['total_amount'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Paid Amount</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['paid_amount'], 0) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['recovery_rate'], 1) }}% recovery rate</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Remaining</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['remaining_amount'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($stats['active_count']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_count']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Completed Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ number_format($stats['completed_count']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600">{{ number_format($stats['overdue_count']) }}</p>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Average Loan Amount</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($stats['avg_loan_amount'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Recovery Rate</p>
            <div class="flex items-center">
                <div class="w-full bg-gray-200 rounded-full h-4 mr-3">
                    <div class="bg-green-600 h-4 rounded-full flex items-center justify-center" style="width: {{ min(100, $stats['recovery_rate']) }}%">
                        <span class="text-xs font-semibold text-white">{{ number_format($stats['recovery_rate'], 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loans by Status Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Loans Distribution by Status</h3>
        <div class="space-y-4">
            @php
                $maxStatusCount = $loansByStatus->max('count') ?? 1;
            @endphp
            @foreach($loansByStatus as $status)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <span class="px-3 py-1 text-xs rounded-full mr-3 {{ 
                                $status->status === 'active' ? 'bg-green-100 text-green-800' : 
                                ($status->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($status->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'))
                            }}">
                                {{ ucfirst($status->status) }}
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($status->count) }} loans</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($status->total, 0) }} TZS</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ 
                            $status->status === 'active' ? 'bg-green-600' : 
                            ($status->status === 'pending' ? 'bg-yellow-600' : 
                            ($status->status === 'completed' ? 'bg-blue-600' : 'bg-gray-600'))
                        }}" style="width: {{ $maxStatusCount > 0 ? ($status->count / $maxStatusCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Loan Applications ({{ date('Y') }})</h3>
        <div class="space-y-4">
            @php
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $maxMonthCount = $loansByMonth->max('count') ?? 1;
            @endphp
            @foreach($months as $index => $month)
                @php
                    $monthData = $loansByMonth->firstWhere('month', $index + 1);
                    $count = $monthData->count ?? 0;
                    $total = $monthData->total ?? 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $count }} loans ({{ number_format($total / 1000, 1) }}K TZS)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-[#015425] h-3 rounded-full" style="width: {{ $maxMonthCount > 0 ? ($count / $maxMonthCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">All Loans</h3>
            <span class="text-sm text-gray-500">Showing {{ $loans->firstItem() ?? 0 }}-{{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-[#015425]">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $loan->user->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-semibold">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $progress = $loan->principal_amount > 0 ? ($loan->paid_amount / $loan->principal_amount) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, $progress) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ number_format($progress, 1) }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($loan->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $loan->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No loans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
