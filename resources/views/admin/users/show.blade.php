@extends('layouts.admin')

@section('page-title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex items-center flex-1">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                    <span class="text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ $user->name }}</h1>
                    <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $user->email }}</p>
                    @if($user->member_number)
                    <p class="text-white text-opacity-80 text-xs mt-1">Member #: {{ $user->member_number }}</p>
                    @endif
                </div>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reset password for this user and send to their email?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition font-medium shadow-md">
                        Reset Password
                    </button>
                </form>
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    @if($user->status)
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ 
                    $user->status === 'active' ? 'bg-green-100 text-green-800' : 
                    ($user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                    ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'))
                }}">
                    Status: {{ ucfirst($user->status) }}
                </span>
                @if($user->status_reason)
                <span class="ml-4 text-sm text-gray-600">{{ $user->status_reason }}</span>
                @endif
            </div>
            @if($user->status_changed_at)
            <span class="text-sm text-gray-500">Changed: {{ $user->status_changed_at->format('M d, Y') }}</span>
            @endif
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['loans']['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['loans']['total_amount'], 0) }} TZS total</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Savings</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $stats['savings']['total_accounts'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['savings']['total_balance'], 0) }} TZS balance</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Investments</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $stats['investments']['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['investments']['total_principal'], 0) }} TZS</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Issues</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ $stats['issues']['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['issues']['open'] }} open</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Full Name</p>
                        <p class="text-lg font-semibold">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email Address</p>
                        <p class="text-lg font-semibold">{{ $user->email }}</p>
                    </div>
                    @if($user->phone)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                        <p class="text-lg font-semibold">{{ $user->phone }}</p>
                    </div>
                    @endif
                    @if($user->alternate_phone)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Alternate Phone</p>
                        <p class="text-lg font-semibold">{{ $user->alternate_phone }}</p>
                    </div>
                    @endif
                    @if($user->date_of_birth)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Date of Birth</p>
                        <p class="text-lg font-semibold">{{ $user->date_of_birth->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Age: {{ $user->date_of_birth->age }} years</p>
                    </div>
                    @endif
                    @if($user->gender)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Gender</p>
                        <p class="text-lg font-semibold">{{ ucfirst($user->gender) }}</p>
                    </div>
                    @endif
                    @if($user->national_id)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">National ID</p>
                        <p class="text-lg font-semibold">{{ $user->national_id }}</p>
                    </div>
                    @endif
                    @if($user->marital_status)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Marital Status</p>
                        <p class="text-lg font-semibold">{{ ucfirst($user->marital_status) }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Member Since</p>
                        <p class="text-lg font-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($user->address || $user->city || $user->region)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Contact Information
                </h3>
                <div class="space-y-3">
                    @if($user->address)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Address</p>
                        <p class="text-lg font-semibold">{{ $user->address }}</p>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($user->city)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">City</p>
                            <p class="text-lg font-semibold">{{ $user->city }}</p>
                        </div>
                        @endif
                        @if($user->region)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Region</p>
                            <p class="text-lg font-semibold">{{ $user->region }}</p>
                        </div>
                        @endif
                        @if($user->postal_code)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Postal Code</p>
                            <p class="text-lg font-semibold">{{ $user->postal_code }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Employment Information -->
            @if($user->occupation || $user->employer || $user->monthly_income)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Employment Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($user->occupation)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Occupation</p>
                        <p class="text-lg font-semibold">{{ $user->occupation }}</p>
                    </div>
                    @endif
                    @if($user->employer)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Employer</p>
                        <p class="text-lg font-semibold">{{ $user->employer }}</p>
                    </div>
                    @endif
                    @if($user->monthly_income)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Monthly Income</p>
                        <p class="text-lg font-semibold">{{ number_format($user->monthly_income, 0) }} TZS</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Loans Overview -->
            @if($stats['loans']['total'] > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#015425] flex items-center">
                        <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Loans Overview
                    </h3>
                    <a href="{{ route('admin.loans.index') }}?user_id={{ $user->id }}" class="text-sm text-[#015425] hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Total Loans</p>
                        <p class="text-xl font-bold text-blue-600">{{ $stats['loans']['total'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Active</p>
                        <p class="text-xl font-bold text-green-600">{{ $stats['loans']['active'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Pending</p>
                        <p class="text-xl font-bold text-yellow-600">{{ $stats['loans']['pending'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Completed</p>
                        <p class="text-xl font-bold text-gray-600">{{ $stats['loans']['completed'] }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Principal</span>
                        <span class="font-semibold">{{ number_format($stats['loans']['total_amount'], 0) }} TZS</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Paid Amount</span>
                        <span class="font-semibold text-green-600">{{ number_format($stats['loans']['paid_amount'], 0) }} TZS</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Remaining</span>
                        <span class="font-semibold text-red-600">{{ number_format($stats['loans']['remaining_amount'], 0) }} TZS</span>
                    </div>
                    @php
                        $recoveryRate = $stats['loans']['total_amount'] > 0 ? ($stats['loans']['paid_amount'] / $stats['loans']['total_amount']) * 100 : 0;
                    @endphp
                    <div class="mt-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Recovery Rate</span>
                            <span class="font-semibold">{{ number_format($recoveryRate, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, $recoveryRate) }}%"></div>
                        </div>
                    </div>
                </div>
                @if($recentLoans->count() > 0)
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Recent Loans</h4>
                    <div class="space-y-2">
                        @foreach($recentLoans as $loan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $loan->loan_number }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($loan->principal_amount, 0) }} TZS • {{ ucfirst($loan->status) }}</p>
                            </div>
                            <a href="{{ route('admin.loans.show', $loan) }}" class="text-[#015425] hover:underline text-sm">View</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Savings Overview -->
            @if($stats['savings']['total_accounts'] > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-[#015425] flex items-center">
                        <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Savings Overview
                    </h3>
                    <a href="{{ route('admin.savings.index') }}?user_id={{ $user->id }}" class="text-sm text-[#015425] hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Total Accounts</p>
                        <p class="text-xl font-bold text-green-600">{{ $stats['savings']['total_accounts'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Active Accounts</p>
                        <p class="text-xl font-bold text-blue-600">{{ $stats['savings']['active_accounts'] }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">Total Balance</p>
                        <p class="text-xl font-bold text-purple-600">{{ number_format($stats['savings']['total_balance'], 0) }} TZS</p>
                    </div>
                </div>
                @if($recentSavings->count() > 0)
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3">Recent Accounts</h4>
                    <div class="space-y-2">
                        @foreach($recentSavings as $account)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $account->account_number }}</p>
                                <p class="text-sm text-gray-600">{{ $account->account_type_name }} • {{ number_format($account->balance, 0) }} TZS</p>
                            </div>
                            <a href="{{ route('admin.savings.show', $account) }}" class="text-[#015425] hover:underline text-sm">View</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Roles & Permissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Roles & Permissions
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Assigned Roles</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $role->name }}</span>
                            @empty
                                <p class="text-gray-500 text-sm">No roles assigned</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Quick Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Investments</span>
                        <div class="text-right">
                            <span class="font-semibold">{{ $stats['investments']['total'] }}</span>
                            <p class="text-xs text-gray-500">{{ number_format($stats['investments']['total_principal'], 0) }} TZS</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Welfare</span>
                        <div class="text-right">
                            <span class="font-semibold">{{ $stats['welfare']['total'] }}</span>
                            <p class="text-xs text-gray-500">{{ number_format($stats['welfare']['total_contributions'] - $stats['welfare']['total_benefits'], 0) }} TZS net</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Transactions</span>
                        <div class="text-right">
                            <span class="font-semibold">{{ $stats['transactions']['total'] }}</span>
                            <p class="text-xs text-gray-500">{{ number_format($stats['transactions']['total_amount'], 0) }} TZS</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KYC Status -->
            @if($user->kyc_status)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">KYC Status</h3>
                <div class="space-y-3">
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ 
                            $user->kyc_status === 'verified' ? 'bg-green-100 text-green-800' : 
                            ($user->kyc_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                        }}">
                            {{ ucfirst($user->kyc_status) }}
                        </span>
                    </div>
                    @if($user->kyc_expiry_date)
                    <div>
                        <p class="text-sm text-gray-600">Expiry Date</p>
                        <p class="font-semibold">{{ $user->kyc_expiry_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Account Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Email Verified</p>
                        <p class="font-semibold">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not verified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-semibold">{{ $user->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($user->notes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Notes</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $user->notes }}</p>
            </div>
            @endif

            <!-- Recent Transactions -->
            @if($recentTransactions->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Recent Transactions</h3>
                <div class="space-y-3">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm font-semibold {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($transaction->amount, 0) }} TZS
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
