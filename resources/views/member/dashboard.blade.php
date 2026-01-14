@extends('layouts.member')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-4 sm:p-6 text-white">
        <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ $user->name }}!</h1>
                <p class="text-sm sm:text-base text-white text-opacity-90">Here's your financial overview</p>
                @if($user->roles->count() > 0)
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($user->roles as $role)
                            <span class="px-2 sm:px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs sm:text-sm font-medium">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-4">
                <div class="text-left sm:text-right">
                    <p class="text-xs sm:text-sm text-white text-opacity-75">Member Since</p>
                    <p class="text-base sm:text-lg font-semibold">{{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Loans Card -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <a href="{{ route('member.loans.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <h3 class="text-xs sm:text-sm text-gray-600 mb-1">Total Loans</h3>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total_loans'] }}</p>
            <div class="mt-2 sm:mt-3 flex flex-col sm:flex-row sm:items-center text-xs sm:text-sm gap-1 sm:gap-0">
                <span class="text-green-600 font-semibold">{{ $stats['active_loans'] }} Active</span>
                <span class="hidden sm:inline mx-2 text-gray-300">|</span>
                <span class="text-gray-600 text-xs sm:text-sm">{{ number_format($stats['remaining_amount'], 0) }} TZS Remaining</span>
            </div>
        </div>

        <!-- Savings Card -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <a href="{{ route('member.savings.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <h3 class="text-xs sm:text-sm text-gray-600 mb-1">Total Savings</h3>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_savings_balance'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 text-xs sm:text-sm">
                <span class="text-gray-600">{{ $stats['total_savings'] }} Accounts</span>
            </div>
        </div>

        <!-- Investments Card -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <a href="{{ route('member.investments.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <h3 class="text-xs sm:text-sm text-gray-600 mb-1">Investments</h3>
            <p class="text-xl sm:text-2xl font-bold text-purple-600">{{ number_format($stats['total_investment_amount'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 flex flex-col sm:flex-row sm:items-center text-xs sm:text-sm gap-1 sm:gap-0">
                <span class="text-green-600 font-semibold">{{ $stats['active_investments'] }} Active</span>
                <span class="hidden sm:inline mx-2 text-gray-300">|</span>
                <span class="text-orange-600">{{ number_format($stats['total_profit'], 0) }} TZS Profit</span>
            </div>
        </div>

        <!-- Welfare Card -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <a href="{{ route('member.welfare.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <h3 class="text-xs sm:text-sm text-gray-600 mb-1">Welfare Fund</h3>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ number_format($stats['total_welfare_contributions'] - $stats['total_welfare_benefits'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 text-xs sm:text-sm">
                <span class="text-green-600">Contributed: {{ number_format($stats['total_welfare_contributions'], 0) }} TZS</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Recent Loans -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-[#015425]">Recent Loans</h2>
                    <a href="{{ route('member.loans.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
                </div>
                <div class="space-y-2 sm:space-y-3">
                    @forelse($loans as $loan)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition gap-2 sm:gap-0">
                            <div class="flex-1">
                                <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $loan->loan_number }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">{{ number_format($loan->principal_amount, 0) }} TZS</p>
                            </div>
                            <div class="flex items-center justify-between sm:block sm:text-right">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                                <p class="text-xs text-gray-500 sm:mt-1">{{ $loan->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4 text-sm">No loans found</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Investments -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-[#015425]">Recent Investments</h2>
                    <a href="{{ route('member.investments.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
                </div>
                <div class="space-y-2 sm:space-y-3">
                    @forelse($investments as $investment)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition gap-2 sm:gap-0">
                            <div class="flex-1">
                                <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $investment->investment_number }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">{{ $investment->plan_type_name }} - {{ number_format($investment->principal_amount, 0) }} TZS</p>
                            </div>
                            <div class="flex items-center justify-between sm:block sm:text-right">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $investment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($investment->status === 'matured' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($investment->status) }}
                                </span>
                                <p class="text-xs text-gray-500 sm:mt-1">{{ $investment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4 text-sm">No investments found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3 sm:mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 sm:space-y-2">
                    <a href="{{ route('member.loans.create') }}" class="px-3 sm:px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-xs sm:text-sm font-medium">
                        Apply for Loan
                    </a>
                    <a href="{{ route('member.savings.create') }}" class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-center text-xs sm:text-sm font-medium">
                        Open Savings
                    </a>
                    <a href="{{ route('member.investments.create') }}" class="px-3 sm:px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition text-center text-xs sm:text-sm font-medium">
                        Start Investment
                    </a>
                    <a href="{{ route('member.issues.create') }}" class="px-3 sm:px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition text-center text-xs sm:text-sm font-medium">
                        Report Issue
                    </a>
                </div>
            </div>

            <!-- Pending Issues -->
            @if($stats['pending_issues'] > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 sm:p-6">
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-bold text-yellow-800">Pending Issues</h3>
                </div>
                <p class="text-xs sm:text-sm text-yellow-700 mb-2 sm:mb-3">You have {{ $stats['pending_issues'] }} pending issue(s)</p>
                <a href="{{ route('member.issues.index') }}" class="text-xs sm:text-sm text-yellow-800 hover:underline font-medium">
                    View Issues →
                </a>
            </div>
            @endif

            <!-- Role-Based Info -->
            @if($isLoanOfficer || $isDepositOfficer || $isInvestmentOfficer || $isChairperson || $isSecretary || $isAccountant)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-blue-800 mb-2">Your Roles</h3>
                <p class="text-xs sm:text-sm text-blue-700 mb-2 sm:mb-3">You have special privileges based on your assigned roles.</p>
                <div class="space-y-1">
                    @if($isLoanOfficer)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Loan Officer</p>
                    @endif
                    @if($isDepositOfficer)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Deposit Officer</p>
                    @endif
                    @if($isInvestmentOfficer)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Investment Officer</p>
                    @endif
                    @if($isChairperson)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Chairperson</p>
                    @endif
                    @if($isSecretary)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Secretary</p>
                    @endif
                    @if($isAccountant)
                        <p class="text-xs sm:text-sm text-blue-800">✓ Accountant</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

