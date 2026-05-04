@extends('layouts.admin')

@section('page-title', 'Loan Defaults Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Defaults Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Analysis of overdue loans, default rates, and collection strategies</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.reports.loans.defaults.pdf') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
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

    <!-- Default Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($defaultStats['total_overdue_loans']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Amount</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($defaultStats['total_overdue_amount'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Avg Overdue Days</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($defaultStats['avg_overdue_days'], 0) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Recovery Potential</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($defaultStats['recovery_potential'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Risk Assessment -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Risk Assessment</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Default Severity Distribution</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-red-800">Critical (>90 days)</span>
                        <span class="text-sm font-bold text-red-600">
                            {{ $overdueLoans->where('maturity_date', '<', now()->subDays(90))->count() }} loans
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <span class="text-sm font-medium text-orange-800">High Risk (60-90 days)</span>
                        <span class="text-sm font-bold text-orange-600">
                            {{ $overdueLoans->where('maturity_date', '>=', now()->subDays(90))->where('maturity_date', '<', now()->subDays(60))->count() }} loans
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="text-sm font-medium text-yellow-800">Medium Risk (30-60 days)</span>
                        <span class="text-sm font-bold text-yellow-600">
                            {{ $overdueLoans->where('maturity_date', '>=', now()->subDays(60))->where('maturity_date', '<', now()->subDays(30))->count() }} loans
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-blue-800">Low Risk (1-30 days)</span>
                        <span class="text-sm font-bold text-blue-600">
                            {{ $overdueLoans->where('maturity_date', '>=', now()->subDays(30))->where('maturity_date', '<', now())->count() }} loans
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Collection Priority</h4>
                <div class="space-y-3">
                    <div class="p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-red-800">Immediate Action Required</span>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">High Priority</span>
                        </div>
                        <p class="text-xs text-red-600">Loans overdue >90 days require immediate legal action</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-orange-800">Intensive Follow-up</span>
                            <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">Medium Priority</span>
                        </div>
                        <p class="text-xs text-orange-600">Loans overdue 60-90 days need daily follow-up</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-yellow-800">Regular Monitoring</span>
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Low Priority</span>
                        </div>
                        <p class="text-xs text-yellow-600">Loans overdue <60 days need weekly monitoring</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Default Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Monthly Default Trend ({{ date('Y') }})</h3>
        <div class="space-y-4">
            @php
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $maxMonthlyDefaults = $overdueByMonth->max('count') ?? 1;
            @endphp
            @foreach($months as $index => $month)
                @php
                    $monthData = $overdueByMonth->firstWhere('month', $index + 1);
                    $count = $monthData->count ?? 0;
                    $total = $monthData->total ?? 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                        <span class="text-sm font-semibold text-red-600">{{ $count }} overdue ({{ number_format($total / 1000, 1) }}K TZS)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-600 h-3 rounded-full" style="width: {{ $maxMonthlyDefaults > 0 ? ($count / $maxMonthlyDefaults) * 100 : 0 }}%"></div>
                    </div>
                    @if($count > 0)
                        <div class="mt-1 text-xs text-gray-500">
                            Average overdue: {{ number_format($total / $count, 0) }} TZS per loan
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Overdue Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Overdue Loans Details</h3>
            <span class="text-sm text-gray-500">Showing {{ $overdueLoans->firstItem() ?? 0 }}-{{ $overdueLoans->lastItem() ?? 0 }} of {{ $overdueLoans->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maturity Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Overdue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($overdueLoans as $loan)
                        @php
                            $daysOverdue = $loan->maturity_date ? now()->diffInDays($loan->maturity_date) : 0;
                            $riskLevel = $daysOverdue > 90 ? 'Critical' : ($daysOverdue > 60 ? 'High' : ($daysOverdue > 30 ? 'Medium' : 'Low'));
                            $riskColor = $daysOverdue > 90 ? 'red' : ($daysOverdue > 60 ? 'orange' : ($daysOverdue > 30 ? 'yellow' : 'blue'));
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-[#015425]">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $loan->user->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $loan->maturity_date ? $loan->maturity_date->format('M d, Y') : 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-red-600">{{ $daysOverdue }} days</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $riskColor }}-100 text-{{ $riskColor }}-800">
                                    {{ $riskLevel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($daysOverdue > 90)
                                    <button class="text-red-600 hover:text-red-800 font-medium">Legal Action</button>
                                @elseif($daysOverdue > 60)
                                    <button class="text-orange-600 hover:text-orange-800 font-medium">Intensive Follow-up</button>
                                @else
                                    <button class="text-blue-600 hover:text-blue-800 font-medium">Send Reminder</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No overdue loans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($overdueLoans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $overdueLoans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
