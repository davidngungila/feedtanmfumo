@extends('layouts.admin')

@section('page-title', 'Dashboard - ' . implode(', ', array_map('ucfirst', $roles)))

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Role Dashboard</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Welcome, {{ $user->name }} - Combined view for your roles</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                @foreach($user->roles as $role)
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Role Sections -->
    @if(isset($data['loan_officer']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Loan Officer Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Pending Approvals</p>
                <p class="text-2xl font-bold text-blue-600">{{ $data['loan_officer']['pending_approvals'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Active Loans</p>
                <p class="text-2xl font-bold text-green-600">{{ $data['loan_officer']['active_loans'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Portfolio</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($data['loan_officer']['total_portfolio'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Overdue Loans</p>
                <p class="text-2xl font-bold text-red-600">{{ $data['loan_officer']['overdue_loans'] }}</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.loans.index') }}" class="text-[#015425] hover:underline font-medium">View All Loans →</a>
        </div>
    </div>
    @endif

    @if(isset($data['deposit_officer']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Deposit Officer Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Accounts</p>
                <p class="text-2xl font-bold text-green-600">{{ $data['deposit_officer']['total_accounts'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Active Accounts</p>
                <p class="text-2xl font-bold text-blue-600">{{ $data['deposit_officer']['active_accounts'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Balance</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($data['deposit_officer']['total_balance'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Today's Deposits</p>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($data['deposit_officer']['today_deposits'] / 1000, 1) }}K TZS</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.savings.index') }}" class="text-[#015425] hover:underline font-medium">View All Savings →</a>
        </div>
    </div>
    @endif

    @if(isset($data['investment_officer']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            Investment Officer Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Investments</p>
                <p class="text-2xl font-bold text-purple-600">{{ $data['investment_officer']['total_investments'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Active Investments</p>
                <p class="text-2xl font-bold text-green-600">{{ $data['investment_officer']['active_investments'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Principal</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($data['investment_officer']['total_principal'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Profit</p>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($data['investment_officer']['total_profit'] / 1000, 1) }}K TZS</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.investments.index') }}" class="text-[#015425] hover:underline font-medium">View All Investments →</a>
        </div>
    </div>
    @endif

    @if(isset($data['chairperson']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Chairperson Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Members</p>
                <p class="text-2xl font-bold text-blue-600">{{ $data['chairperson']['total_members'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Loans</p>
                <p class="text-2xl font-bold text-green-600">{{ $data['chairperson']['total_loans'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Savings</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($data['chairperson']['total_savings'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Pending Issues</p>
                <p class="text-2xl font-bold text-orange-600">{{ $data['chairperson']['pending_issues'] }}</p>
            </div>
        </div>
        @if(isset($data['chairperson']['financial_summary']))
        <div class="mt-6 bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Financial Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-white text-opacity-80 mb-1">Total Assets</p>
                    <p class="text-2xl font-bold">{{ number_format($data['chairperson']['financial_summary']['total_assets'] / 1000, 1) }}K TZS</p>
                </div>
                <div>
                    <p class="text-sm text-white text-opacity-80 mb-1">Total Liabilities</p>
                    <p class="text-2xl font-bold">{{ number_format($data['chairperson']['financial_summary']['total_liabilities'] / 1000, 1) }}K TZS</p>
                </div>
                <div>
                    <p class="text-sm text-white text-opacity-80 mb-1">Net Position</p>
                    <p class="text-2xl font-bold">{{ number_format($data['chairperson']['financial_summary']['net_position'] / 1000, 1) }}K TZS</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    @if(isset($data['secretary']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Secretary Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Members</p>
                <p class="text-2xl font-bold text-blue-600">{{ $data['secretary']['total_members'] }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Pending Issues</p>
                <p class="text-2xl font-bold text-orange-600">{{ $data['secretary']['pending_issues'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Resolved Issues</p>
                <p class="text-2xl font-bold text-green-600">{{ $data['secretary']['resolved_issues'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">New Members This Month</p>
                <p class="text-2xl font-bold text-purple-600">{{ $data['secretary']['member_registrations_this_month'] }}</p>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.users.index') }}" class="text-[#015425] hover:underline font-medium">View All Members →</a>
        </div>
    </div>
    @endif

    @if(isset($data['accountant']))
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Accountant Dashboard
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($data['accountant']['total_revenue'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($data['accountant']['total_expenses'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Net Profit</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($data['accountant']['net_profit'] / 1000, 1) }}K TZS</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Total Transactions</p>
                <p class="text-2xl font-bold text-purple-600">{{ $data['accountant']['total_transactions'] }}</p>
            </div>
        </div>
        @if(isset($data['accountant']['financial_summary']))
        <div class="mt-6">
            <h3 class="font-semibold text-gray-800 mb-3">Financial Overview</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Loans</p>
                    <p class="text-lg font-bold">{{ number_format($data['accountant']['financial_summary']['total_loans'] / 1000, 1) }}K TZS</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Outstanding</p>
                    <p class="text-lg font-bold">{{ number_format($data['accountant']['financial_summary']['outstanding'] / 1000, 1) }}K TZS</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Savings</p>
                    <p class="text-lg font-bold">{{ number_format($data['accountant']['financial_summary']['total_savings'] / 1000, 1) }}K TZS</p>
                </div>
            </div>
        </div>
        @endif
        <div class="mt-6">
            <a href="{{ route('admin.reports.index') }}" class="text-[#015425] hover:underline font-medium">View All Reports →</a>
        </div>
    </div>
    @endif
</div>
@endsection

