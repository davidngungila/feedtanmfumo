@extends('layouts.admin')

@section('page-title', 'Accountant Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Accountant Dashboard</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Welcome, {{ $user->name }} - Financial overview and accounting</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    View Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($data['total_revenue'] / 1000, 1) }}K TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($data['total_expenses'] / 1000, 1) }}K TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Net Profit</p>
            <p class="text-2xl sm:text-3xl font-bold {{ $data['net_profit'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">{{ number_format($data['net_profit'] / 1000, 1) }}K TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Transactions</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $data['total_transactions'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $data['today_transactions'] }} today</p>
        </div>
    </div>

    <!-- Financial Overview -->
    @if(isset($data['financial_summary']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-6">Financial Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Loans</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($data['financial_summary']['total_loans'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Paid Amount</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($data['financial_summary']['total_paid'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Outstanding</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($data['financial_summary']['outstanding'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Savings</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($data['financial_summary']['total_savings'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Investments</p>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($data['financial_summary']['total_investments'] / 1000, 1) }}K TZS</p>
            </div>
        </div>
    </div>
    @endif

    <!-- This Month's Revenue -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-4">This Month's Revenue</h3>
        <div class="flex items-center">
            <div class="flex-1">
                <p class="text-sm text-gray-600 mb-1">Monthly Revenue</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($data['monthly_revenue'], 0) }} TZS</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    @if($data['recent_transactions']->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Recent Transactions</h3>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-[#015425] hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['recent_transactions']->take(15) as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $transaction->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($transaction->amount, 0) }} TZS
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- My Assigned Issues -->
    @if(isset($data['my_issues']) && $data['my_issues']->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">My Assigned Issues</h3>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ $data['my_pending_issues'] }} Pending</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $data['my_in_progress_issues'] }} In Progress</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ $data['my_resolved_issues'] }} Resolved</span>
                </div>
                <a href="{{ route('admin.issues.index', ['assigned_to' => $user->id]) }}" class="text-sm text-[#015425] hover:underline">View All ({{ $data['total_my_issues'] }}) →</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['my_issues'] as $issue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $issue->issue_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ Str::limit($issue->title, 40) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ ucfirst($issue->category) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ 
                                $issue->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                ($issue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                ($issue->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'))
                            }}">
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ 
                                $issue->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                ($issue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                ($issue->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $issue->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.issues.show', $issue) }}" class="text-[#015425] hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(isset($data['total_my_issues']) && $data['total_my_issues'] == 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Assigned Issues</h3>
            <p class="text-sm text-gray-600">You don't have any issues assigned to you at the moment.</p>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.reports.financial') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Financial Reports</h3>
            </div>
            <p class="text-sm text-gray-600">View detailed financial reports</p>
        </a>

        <a href="{{ route('admin.reports.loans') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Loan Reports</h3>
            </div>
            <p class="text-sm text-gray-600">View loan-related reports</p>
        </a>

        <a href="{{ route('admin.reports.savings') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Savings Reports</h3>
            </div>
            <p class="text-sm text-gray-600">View savings account reports</p>
        </a>
    </div>
</div>
@endsection

