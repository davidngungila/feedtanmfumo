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

    <!-- Membership Application Status -->
    @if($user->membership_type_id)
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 
        {{ $user->membership_status === 'approved' ? 'border-green-500' : '' }}
        {{ $user->membership_status === 'pending' ? 'border-yellow-500' : '' }}
        {{ $user->membership_status === 'rejected' ? 'border-red-500' : '' }}
        {{ $user->membership_status === 'suspended' ? 'border-orange-500' : '' }}">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center
                        {{ $user->membership_status === 'approved' ? 'bg-green-100' : '' }}
                        {{ $user->membership_status === 'pending' ? 'bg-yellow-100' : '' }}
                        {{ $user->membership_status === 'rejected' ? 'bg-red-100' : '' }}
                        {{ $user->membership_status === 'suspended' ? 'bg-orange-100' : '' }}">
                        @if($user->membership_status === 'approved')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($user->membership_status === 'pending')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($user->membership_status === 'rejected')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($user->membership_status === 'suspended')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold 
                            {{ $user->membership_status === 'approved' ? 'text-green-700' : '' }}
                            {{ $user->membership_status === 'pending' ? 'text-yellow-700' : '' }}
                            {{ $user->membership_status === 'rejected' ? 'text-red-700' : '' }}
                            {{ $user->membership_status === 'suspended' ? 'text-orange-700' : '' }}">
                            Membership Application Status
                        </h3>
                        <p class="text-sm sm:text-base text-[#015425] mt-1">
                            @if($user->membership_status === 'approved')
                                <span class="font-semibold text-green-700">✓ Approved</span>
                                @if($user->membershipType)
                                    <span class="text-[#015425]">- {{ $user->membershipType->name }}</span>
                                @endif
                            @elseif($user->membership_status === 'pending')
                                <span class="font-semibold text-yellow-700">⏳ In Approval / Pending Review</span>
                                <span class="text-[#015425] block mt-1 text-xs sm:text-sm">Your application is being reviewed by administrators</span>
                            @elseif($user->membership_status === 'rejected')
                                <span class="font-semibold text-red-700">✗ Rejected</span>
                                <span class="text-[#015425] block mt-1 text-xs sm:text-sm">Your application has been rejected. Please contact support for more information.</span>
                            @elseif($user->membership_status === 'suspended')
                                <span class="font-semibold text-orange-700">⚠ Suspended</span>
                                <span class="text-[#015425] block mt-1 text-xs sm:text-sm">Your membership has been suspended. Please contact support.</span>
                            @endif
                        </p>
                        @if($user->membership_code)
                            <p class="text-xs sm:text-sm text-[#015425] mt-1">Membership Code: <span class="font-mono font-semibold">{{ $user->membership_code }}</span></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                @if($user->membership_status === 'pending')
                    <a href="{{ route('member.membership.status') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition text-center text-sm font-medium">
                        View Status
                    </a>
                    @php
                        $nextStep = $user->membership_application_current_step ?? 1;
                        if ($nextStep == 0) $nextStep = 1;
                    @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="px-4 py-2 border border-yellow-600 text-yellow-700 rounded-md hover:bg-yellow-50 transition text-center text-sm font-medium">
                        Continue Application
                    </a>
                @elseif($user->membership_status === 'rejected')
                    <a href="{{ route('member.membership.status') }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-center text-sm font-medium">
                        View Details
                    </a>
                @elseif($user->membership_status === 'suspended')
                    <a href="{{ route('member.membership.status') }}" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition text-center text-sm font-medium">
                        View Details
                    </a>
                @elseif($user->membership_status === 'approved')
                    <a href="{{ route('member.membership.status') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-center text-sm font-medium">
                        View Membership
                    </a>
                @endif
            </div>
        </div>
        
        @if($user->membership_status === 'pending')
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-[#015425]">
                        <strong>Note:</strong> While your membership application is pending approval, some features may be limited. 
                        You can still access your dashboard and update your application.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @else
    <!-- No Membership Application -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-blue-900 mb-2">Complete Your Membership Application</h3>
                <p class="text-sm text-blue-800 mb-4">You haven't submitted a membership application yet. Complete your application to access all features.</p>
                <a href="{{ route('member.membership.step1') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                    Start Application
                </a>
            </div>
        </div>
    </div>
    @endif

    @if($user->membership_status === 'approved')
    <!-- Dashboard Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="flex border-b border-gray-100">
            <button class="flex-1 py-3 px-4 text-sm font-bold text-[#015425] border-b-2 border-[#015425] transition bg-green-50/50">
                Financial Overview
            </button>
            <button onclick="document.getElementById('payment-verifications-section').scrollIntoView({behavior: 'smooth'})" class="flex-1 py-3 px-4 text-sm font-semibold text-gray-500 hover:text-[#015425] hover:bg-gray-50 transition border-b-2 border-transparent">
                Payment Verifications
            </button>
        </div>
    </div>

    <!-- Detailed Statistics Overview -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg sm:text-xl font-bold text-[#015425]">Financial Overview</h2>
            <div class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase tracking-wider">Live Status</div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                <p class="text-[10px] sm:text-xs text-gray-600 mb-1 uppercase tracking-tight">Total Assets</p>
                <p class="text-base sm:text-lg font-bold text-blue-600">{{ number_format($stats['total_savings_balance'] + $stats['total_investment_amount'] + $stats['total_profit'], 0) }} <span class="text-[10px]">TZS</span></p>
            </div>
            <div class="text-center p-3 bg-green-50 rounded-lg border border-green-100">
                <p class="text-[10px] sm:text-xs text-gray-600 mb-1 uppercase tracking-tight">Total Income</p>
                <p class="text-base sm:text-lg font-bold text-green-600">{{ number_format($stats['total_income'], 0) }} <span class="text-[10px]">TZS</span></p>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-lg border border-red-100">
                <p class="text-[10px] sm:text-xs text-gray-600 mb-1 uppercase tracking-tight">Total Expenses</p>
                <p class="text-base sm:text-lg font-bold text-red-600">{{ number_format($stats['total_expenses'], 0) }} <span class="text-[10px]">TZS</span></p>
            </div>
            <div class="text-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                <p class="text-[10px] sm:text-xs text-gray-600 mb-1 uppercase tracking-tight">Net Worth</p>
                <p class="text-base sm:text-lg font-bold text-purple-600">{{ number_format(($stats['total_savings_balance'] + $stats['total_investment_amount'] + $stats['total_profit']) - $stats['remaining_amount'], 0) }} <span class="text-[10px]">TZS</span></p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
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
            <h3 class="text-xs sm:text-sm text-[#015425] mb-1">Total Loans</h3>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total_loans'] }}</p>
            <div class="mt-2 sm:mt-3 space-y-1">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Active:</span>
                    <span class="text-green-600 font-semibold">{{ $stats['active_loans'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Pending:</span>
                    <span class="text-yellow-600 font-semibold">{{ $stats['pending_loans'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Remaining:</span>
                    <span class="text-[#015425] font-semibold">{{ number_format($stats['remaining_amount'], 0) }} TZS</span>
                </div>
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
            <h3 class="text-xs sm:text-sm text-[#015425] mb-1">Total Savings</h3>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_savings_balance'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 space-y-1">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Accounts:</span>
                    <span class="text-[#015425] font-semibold">{{ $stats['total_savings'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Active:</span>
                    <span class="text-green-600 font-semibold">{{ $stats['active_savings'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Deposits:</span>
                    <span class="text-blue-600 font-semibold">{{ number_format($stats['total_savings_deposits'], 0) }} TZS</span>
                </div>
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
            <h3 class="text-xs sm:text-sm text-[#015425] mb-1">Investments</h3>
            <p class="text-xl sm:text-2xl font-bold text-purple-600">{{ number_format($stats['total_investment_amount'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 space-y-1">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Active:</span>
                    <span class="text-green-600 font-semibold">{{ $stats['active_investments'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Profit Earned:</span>
                    <span class="text-orange-600 font-semibold">{{ number_format($stats['total_profit'], 0) }} TZS</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Expected:</span>
                    <span class="text-blue-600 font-semibold">{{ number_format($stats['expected_profit'], 0) }} TZS</span>
                </div>
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
            <h3 class="text-xs sm:text-sm text-[#015425] mb-1">Welfare Fund</h3>
            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ number_format($stats['welfare_balance'], 0) }} TZS</p>
            <div class="mt-2 sm:mt-3 space-y-1">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Contributed:</span>
                    <span class="text-green-600 font-semibold">{{ number_format($stats['total_welfare_contributions'], 0) }} TZS</span>
                </div>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Benefits:</span>
                    <span class="text-blue-600 font-semibold">{{ number_format($stats['total_welfare_benefits'], 0) }} TZS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Verifications Section -->
    <div id="payment-verifications-section" class="bg-white rounded-lg shadow-md mb-6 border border-gray-100">
        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-green-50 to-white rounded-t-lg">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[#015425]">Uthibitishaji wa Malipo (Divine Verification)</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Gawa gawio lako utakavyo - Akiba au FIA</p>
            </div>
            <a href="{{ route('member.payment-confirmations.index') }}" class="px-3 sm:px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-xs sm:text-sm font-bold shadow-sm">
                Thibitisha Sasa
            </a>
        </div>
        <div class="p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="pb-3 px-2">Taarifa ya Malipo</th>
                            <th class="pb-3 px-2">Kiasi (TZS)</th>
                            <th class="pb-3 px-2">Aina ya Mgawanyo</th>
                            <th class="pb-3 px-2">Status</th>
                            <th class="pb-3 px-2">Tarehe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($paymentConfirmations as $confirmation)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="py-3 px-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#015425]">{{ $confirmation->member_id }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono">{{ $confirmation->member_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-2">
                                    <p class="text-sm font-bold text-gray-800">{{ number_format($confirmation->amount_to_pay, 0) }}</p>
                                    <p class="text-[10px] text-green-600 font-medium">+ {{ number_format($confirmation->total_distribution, 0) }} allocated</p>
                                </td>
                                <td class="py-3 px-2">
                                    <div class="flex flex-wrap gap-1">
                                        @if($confirmation->re_deposit > 0)
                                            <span class="text-[9px] px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded font-bold uppercase tracking-tighter">Akiba</span>
                                        @endif
                                        @if($confirmation->fia_investment > 0)
                                            <span class="text-[9px] px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded font-bold uppercase tracking-tighter">FIA</span>
                                        @endif
                                        @php
                                            $cashRemaining = $confirmation->amount_to_pay - $confirmation->swf_contribution - $confirmation->loan_repayment - $confirmation->capital_contribution - $confirmation->fine_penalty - $confirmation->re_deposit - $confirmation->fia_investment;
                                        @endphp
                                        @if($cashRemaining > 0)
                                            <span class="text-[9px] px-1.5 py-0.5 bg-green-50 text-green-700 rounded font-bold uppercase tracking-tighter">Cash</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-2">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-widest bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                </td>
                                <td class="py-3 px-2">
                                    <p class="text-[11px] text-gray-500 font-medium">{{ $confirmation->created_at->format('d M, Y') }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $confirmation->created_at->format('H:i') }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400">Hakuna kumbukumbu za malipo bado</p>
                                        <p class="text-xs text-gray-300 mt-1">Anza kwa kuthibitisha malipo yako ya kwanza</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 pb-2 sm:pb-4 border-b border-gray-50 last:border-0 md:border-0">
            <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3">Transaction Summary</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Transactions</span>
                    <span class="text-sm font-semibold text-[#015425]">{{ $stats['total_transactions'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Income</span>
                    <span class="text-sm font-semibold text-green-600 font-mono">{{ number_format($stats['total_income'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Expenses</span>
                    <span class="text-sm font-semibold text-red-600 font-mono">{{ number_format($stats['total_expenses'], 0) }}</span>
                </div>
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-[#015425]">Net Cash Flow</span>
                        <span class="text-sm font-bold font-mono {{ ($stats['total_income'] - $stats['total_expenses']) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($stats['total_income'] - $stats['total_expenses'], 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 last:border-0 md:border-0">
            <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3">Loan Performance</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Borrowed</span>
                    <span class="text-sm font-semibold text-[#015425] font-mono">{{ number_format($stats['total_loan_amount'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Amount Paid</span>
                    <span class="text-sm font-semibold text-green-600 font-mono">{{ number_format($stats['paid_amount'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Remaining</span>
                    <span class="text-sm font-semibold text-orange-600 font-mono">{{ number_format($stats['remaining_amount'], 0) }}</span>
                </div>
                @if($stats['total_loan_amount'] > 0)
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-[#015425]">Repayment Rate</span>
                        <div class="flex items-center gap-2">
                             <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ ($stats['paid_amount'] / $stats['total_loan_amount']) * 100 }}%"></div>
                             </div>
                             <span class="text-sm font-bold text-blue-600">
                                {{ number_format(($stats['paid_amount'] / $stats['total_loan_amount']) * 100, 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 last:mb-0 md:mb-0">
            <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3">Investment Performance</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Invested</span>
                    <span class="text-sm font-semibold text-[#015425] font-mono">{{ number_format($stats['total_investment_amount'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Profit Earned</span>
                    <span class="text-sm font-semibold text-green-600 font-mono">{{ number_format($stats['total_profit'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Matured</span>
                    <span class="text-sm font-semibold text-blue-600">{{ $stats['matured_investments'] }}</span>
                </div>
                @if($stats['total_investment_amount'] > 0)
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-[#015425]">Return ROI %</span>
                        <span class="text-sm font-bold text-purple-600">
                            {{ number_format(($stats['total_profit'] / $stats['total_investment_amount']) * 100, 2) }}%
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($user->membership_status === 'approved')
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
                                <p class="font-semibold text-sm sm:text-base text-[#015425]">{{ $loan->loan_number }}</p>
                                <p class="text-xs sm:text-sm text-[#015425]">{{ number_format($loan->principal_amount, 0) }} TZS</p>
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
                        <p class="text-[#015425] text-center py-4 text-sm">No loans found</p>
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
                                <p class="font-semibold text-sm sm:text-base text-[#015425]">{{ $investment->investment_number }}</p>
                                <p class="text-xs sm:text-sm text-[#015425]">{{ $investment->plan_type_name }} - {{ number_format($investment->principal_amount, 0) }} TZS</p>
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
                        <p class="text-[#015425] text-center py-4 text-sm">No investments found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3 sm:mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 sm:space-y-2">
                    <a href="{{ route('member.payment-confirmations.index') }}" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-center text-xs sm:text-sm font-bold shadow-sm">
                        Verify Dividend Payment
                    </a>
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

