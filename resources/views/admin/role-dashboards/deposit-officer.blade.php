@extends('layouts.admin')

@section('page-title', 'Deposit Officer Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Deposit Officer Dashboard</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Welcome, {{ $user->name }} - Manage savings operations</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.savings.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    New Account
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $data['total_accounts'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Accounts</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $data['active_accounts'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Balance</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($data['total_balance'] / 1000, 1) }}K TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Today's Deposits</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($data['today_deposits'] / 1000, 1) }}K TZS</p>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Today's Activity</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Deposits</span>
                    <span class="font-semibold text-green-600">{{ number_format($data['today_deposits'], 0) }} TZS</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Withdrawals</span>
                    <span class="font-semibold text-red-600">{{ number_format($data['today_withdrawals'], 0) }} TZS</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-medium text-gray-900">Net Flow</span>
                    <span class="font-bold text-lg {{ ($data['today_deposits'] - $data['today_withdrawals']) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($data['today_deposits'] - $data['today_withdrawals'], 0) }} TZS
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">This Month</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Deposits</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($data['monthly_deposits'], 0) }} TZS</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Accounts -->
    @if($data['recent_accounts']->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Recent Accounts</h3>
            <a href="{{ route('admin.savings.index') }}" class="text-sm text-[#015425] hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['recent_accounts'] as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $account->account_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $account->user->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $account->account_type_name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ number_format($account->balance, 0) }} TZS</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full {{ $account->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($account->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.savings.show', $account) }}" class="text-[#015425] hover:underline">View</a>
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
        <a href="{{ route('admin.savings.deposits') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Deposit Transactions</h3>
            </div>
            <p class="text-sm text-gray-600">Process deposit transactions</p>
        </a>

        <a href="{{ route('admin.savings.withdrawals') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Withdrawal Requests</h3>
            </div>
            <p class="text-sm text-gray-600">Process withdrawal requests</p>
        </a>

        <a href="{{ route('admin.savings.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Open New Account</h3>
            </div>
            <p class="text-sm text-gray-600">Create a new savings account</p>
        </a>
    </div>
</div>
@endsection

