@extends('layouts.admin')

@section('page-title', 'Loan Officer Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Officer Dashboard</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Welcome, {{ $user->name }} - Manage loan operations</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.loans.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    New Loan Application
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Approvals</p>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $data['pending_approvals'] }}</p>
            <a href="{{ route('admin.loans.pending-approvals') }}" class="text-xs text-[#015425] hover:underline mt-1 block">View Details →</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $data['active_loans'] }}</p>
            <a href="{{ route('admin.loans.active') }}" class="text-xs text-[#015425] hover:underline mt-1 block">View Details →</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Portfolio</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($data['total_portfolio'] / 1000, 1) }}K TZS</p>
            <a href="{{ route('admin.loans.portfolio') }}" class="text-xs text-[#015425] hover:underline mt-1 block">View Portfolio →</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ $data['overdue_loans'] }}</p>
            <a href="{{ route('admin.loans.overdue') }}" class="text-xs text-[#015425] hover:underline mt-1 block">View Details →</a>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#015425] mb-4">This Month's Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">New Loan Applications</p>
                <p class="text-2xl font-bold text-[#015425]">{{ $data['monthly_loans'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount This Month</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($data['monthly_amount'], 0) }} TZS</p>
            </div>
        </div>
    </div>

    <!-- Recent Pending Applications -->
    @if($data['pending_applications']->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Recent Pending Applications</h3>
            <a href="{{ route('admin.loans.pending-approvals') }}" class="text-sm text-[#015425] hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['pending_applications'] as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $loan->loan_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $loan->user->name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $loan->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:underline">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Approvals -->
    @if($data['recent_approvals']->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-xl font-bold text-[#015425]">Recent Approvals</h3>
            <a href="{{ route('admin.loans.index', ['status' => 'approved']) }}" class="text-sm text-[#015425] hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['recent_approvals'] as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $loan->loan_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $loan->user->name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $loan->approval_date ? $loan->approval_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:underline">View</a>
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
        <a href="{{ route('admin.loans.create') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">New Loan Application</h3>
            </div>
            <p class="text-sm text-gray-600">Create a new loan application for a member</p>
        </a>

        <a href="{{ route('admin.loans.credit-assessment') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Credit Assessment</h3>
            </div>
            <p class="text-sm text-gray-600">Assess creditworthiness of applicants</p>
        </a>

        <a href="{{ route('admin.loans.due-payments') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 ml-4">Due Payments</h3>
            </div>
            <p class="text-sm text-gray-600">View upcoming loan payments</p>
        </a>
    </div>
</div>
@endsection

