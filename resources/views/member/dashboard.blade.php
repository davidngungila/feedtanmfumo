@extends('layouts.member')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Premium Welcome Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Here's your member account snapshot for today.</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('l, F d, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1"></path>
                        </svg>
                        <span>Member since {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('member.profile.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    View Profile
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-lg sm:text-xl font-black text-gray-900">Quick Services</h2>
                <p class="text-sm text-gray-500">Fast access to your most-used member services.</p>
            </div>
            <a href="{{ route('member.guide') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-50 text-gray-700 rounded-md border border-gray-200 hover:bg-gray-100 transition font-medium text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Member Guide
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
            <a href="{{ route('member.loans.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-blue-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-blue-700">Loans</p>
                <p class="text-xs text-gray-500">Requests & status</p>
            </a>

            <a href="{{ route('member.savings.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-green-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-green-50 flex items-center justify-center text-[#015425] mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-[#015425]">Savings</p>
                <p class="text-xs text-gray-500">Accounts</p>
            </a>

            <a href="{{ route('member.savings.create') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-emerald-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-emerald-700">Saving Plan</p>
                <p class="text-xs text-gray-500">Open account</p>
            </a>

            <a href="{{ route('member.investments.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-purple-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-purple-50 flex items-center justify-center text-purple-600 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-purple-700">Investments</p>
                <p class="text-xs text-gray-500">Portfolio</p>
            </a>

            <a href="{{ route('member.welfare.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-amber-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-amber-50 flex items-center justify-center text-amber-700 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-amber-800">SWF</p>
                <p class="text-xs text-gray-500">Welfare</p>
            </a>

            <a href="{{ route('member.issues.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-orange-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-orange-50 flex items-center justify-center text-orange-600 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-orange-700">Issues</p>
                <p class="text-xs text-gray-500">Support</p>
            </a>

            <a href="{{ route('member.monthly-deposits.index') }}" class="group flex flex-col items-start p-4 rounded-lg border border-gray-100 hover:border-slate-200 hover:shadow-md transition">
                <div class="w-10 h-10 rounded-md bg-slate-50 flex items-center justify-center text-slate-600 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h12"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h12"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15h3"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h.01M5 11h.01M5 15h.01"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-900 group-hover:text-slate-700">Transactions</p>
                <p class="text-xs text-gray-500">Statements</p>
            </a>
        </div>
    </div>

    <!-- Tier 1: Critical Status & Membership -->
    @if($user->membership_type_id)
    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8 relative overflow-hidden group">
        @php
            $statusColors = [
                'approved' => 'from-green-500 to-emerald-600',
                'pending' => 'from-yellow-400 to-orange-500',
                'rejected' => 'from-red-400 to-pink-600',
                'suspended' => 'from-gray-600 to-slate-800'
            ];
            $grad = $statusColors[$user->membership_status] ?? 'from-gray-400 to-gray-600';
        @endphp
        <div class="absolute right-0 top-0 w-2 h-full bg-gradient-to-b {{ $grad }}"></div>
        
        <div class="flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="flex items-center gap-8 text-center lg:text-left">
                <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center shadow-inner">
                    @if($user->membership_status === 'approved')
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($user->membership_status === 'pending')
                         <svg class="w-10 h-10 text-yellow-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @else
                         <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">Membership Verification</h3>
                    <p class="text-2xl font-black text-gray-900 leading-tight">
                        @if($user->membership_status === 'approved')
                            Certified Community Member
                        @elseif($user->membership_status === 'pending')
                            Verification in Progress
                        @else
                            Account Status: {{ ucfirst($user->membership_status) }}
                        @endif
                    </p>
                    @if($user->membership_code)
                        <p class="text-xs font-bold text-[#015425] mt-2 bg-green-50 px-3 py-1 rounded-full inline-block">Unique Code: {{ $user->membership_code }}</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                 @if($user->membership_status === 'pending')
                    @php $nextStep = ($user->membership_application_current_step == 0) ? 1 : $user->membership_application_current_step; @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="px-10 py-4 bg-yellow-500 text-white rounded-2xl font-black text-xs shadow-xl hover:bg-yellow-600 transition-all flex items-center gap-3">
                         Complete Onboarding
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @else
                    <a href="{{ route('member.membership.status') }}" class="px-10 py-4 bg-gray-900 text-white rounded-2xl font-black text-xs shadow-xl hover:-translate-y-1 transition-all">Digital ID Card</a>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="bg-indigo-600 rounded-lg p-6 sm:p-8 text-white shadow-lg flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
        <div class="relative z-10 text-center md:text-left">
            <h3 class="text-3xl font-black mb-4">Start Your Growth Journey</h3>
            <p class="text-indigo-100 text-lg opacity-80 max-w-xl">You haven't initiated your membership yet. Join thousands of members and access premium community capital.</p>
        </div>
        <a href="{{ route('member.membership.step1') }}" class="relative z-10 px-12 py-5 bg-white text-indigo-600 rounded-[1.5rem] font-black text-sm shadow-2xl hover:scale-105 transition-all">Initialize Application</a>
    </div>
    @endif

    @if($user->membership_status === 'approved')
    <!-- Tier 2: Asset Grid -->
    <div id="quick-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Savings -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Savings</p>
                    <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total_savings_balance'], 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600 font-medium">{{ $stats['total_savings'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">accounts</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('member.savings.index') }}" class="text-xs font-medium text-[#015425] hover:underline">View savings</a>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Loans</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['active_loans']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600 font-medium">{{ number_format($stats['remaining_amount'], 0) }}</span>
                        <span class="text-xs text-gray-500 ml-1">remaining</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('member.loans.index') }}" class="text-xs font-medium text-blue-600 hover:underline">View loans</a>
            </div>
        </div>

        <!-- Investments -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Investments</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['total_investment_amount'], 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600 font-medium">{{ $stats['active_investments'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">active</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('member.investments.index') }}" class="text-xs font-medium text-purple-600 hover:underline">View investments</a>
            </div>
        </div>

        <!-- Pending Issues -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Issues</p>
                    <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($stats['pending_issues']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600 font-medium">{{ number_format($stats['total_issues']) }}</span>
                        <span class="text-xs text-gray-500 ml-1">total</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('member.issues.index') }}" class="text-xs font-medium text-orange-600 hover:underline">View issues</a>
            </div>
        </div>
    </div>

    <!-- Tier 3: Operational Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main: Dividend Verification -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-10 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 mb-1">Divine Verification</h2>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Dividend distribution & allocation</p>
                    </div>
                    <a href="{{ route('member.payment-confirmations.index') }}" class="px-8 py-3.5 bg-[#015425] text-white rounded-[1.25rem] font-bold text-xs shadow-xl hover:-translate-y-1 transition-all">
                        Initiate Global Verification
                    </a>
                </div>

                <div class="overflow-x-auto p-4 sm:p-8">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                <th class="pb-6 px-4">Entity</th>
                                <th class="pb-6 px-4">Net Value</th>
                                <th class="pb-6 px-4">Allocations</th>
                                <th class="pb-6 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50/50">
                            @forelse($paymentConfirmations as $confirmation)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 px-4">
                                    <div class="flex items-center gap-4 text-xs font-bold text-gray-900">
                                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p>{{ $confirmation->member_name }}</p>
                                            <p class="text-[9px] text-gray-400 tracking-tighter">{{ $confirmation->created_at->format('d M, Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-4">
                                    <p class="text-sm font-black text-gray-900">{{ number_format($confirmation->amount_to_pay, 0) }}</p>
                                    <p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">TZS</p>
                                </td>
                                <td class="py-6 px-4">
                                    <div class="flex flex-wrap gap-1.5 capitalize">
                                        @if($confirmation->re_deposit > 0) <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-black">Akiba</span> @endif
                                        @if($confirmation->fia_investment > 0) <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded text-[9px] font-black">FIA</span> @endif
                                    </div>
                                </td>
                                <td class="py-6 px-4">
                                    <span class="flex items-center gap-1.5 text-[9px] font-black text-green-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Verified
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No Recent Verifications Found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Performance Analytics Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                     <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
                     <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-8">Loan Repayment Dynamics</h3>
                     <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">Repayment Rate</p>
                                <p class="text-4xl font-black text-white">
                                    {{ $stats['total_loan_amount'] > 0 ? number_format(($stats['paid_amount'] / $stats['total_loan_amount']) * 100, 1) : 0 }}<span class="text-sm font-normal opacity-40">%</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-gray-500 uppercase mb-1">Net Paid</p>
                                <p class="text-lg font-black text-green-400">{{ number_format($stats['paid_amount'], 0) }}</p>
                            </div>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                             <div class="h-full bg-green-500 rounded-full" style="width: {{ $stats['total_loan_amount'] > 0 ? ($stats['paid_amount'] / $stats['total_loan_amount']) * 100 : 0 }}%"></div>
                        </div>
                     </div>
                 </div>

                 <div class="bg-[#015425] rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                     <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
                     <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-green-200 mb-8">Investment Yield ROI</h3>
                     <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[9px] font-bold text-green-200 uppercase mb-1">Portfolio Return</p>
                                <p class="text-4xl font-black text-white">
                                    {{ $stats['total_investment_amount'] > 0 ? number_format(($stats['total_profit'] / $stats['total_investment_amount']) * 100, 1) : 0 }}<span class="text-sm font-normal opacity-40">%</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-green-200 uppercase mb-1">Realized Profit</p>
                                <p class="text-lg font-black text-orange-400">{{ number_format($stats['total_profit'], 0) }}</p>
                            </div>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                             <div class="h-full bg-green-400 rounded-full" style="width: {{ $stats['total_investment_amount'] > 0 ? min(100, ($stats['total_profit'] / $stats['total_investment_amount']) * 100) : 0 }}%"></div>
                        </div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Sidebar: Intelligence & Quick Actions -->
        <div class="space-y-8">
            <!-- Action Console -->
            <div class="bg-white rounded-lg p-6 sm:p-8 shadow-md border border-gray-100">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-8">Operations Console</h3>
                <div class="space-y-4">
                    <a href="{{ route('member.loans.create') }}" class="flex items-center justify-between p-5 bg-blue-50/50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all group overflow-hidden relative">
                        <div class="relative z-10 flex items-center gap-4">
                             <div class="w-10 h-10 bg-white shadow-sm flex items-center justify-center rounded-xl text-blue-600 group-hover:text-blue-600">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             </div>
                             <span class="text-xs font-black uppercase tracking-widest">Credit Request</span>
                        </div>
                        <svg class="relative z-10 w-5 h-5 text-blue-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('member.savings.create') }}" class="flex items-center justify-between p-5 bg-green-50/50 rounded-2xl hover:bg-[#015425] hover:text-white transition-all group overflow-hidden relative">
                         <div class="relative z-10 flex items-center gap-4">
                             <div class="w-10 h-10 bg-white shadow-sm flex items-center justify-center rounded-xl text-[#015425] group-hover:text-[#015425]">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                             </div>
                             <span class="text-xs font-black uppercase tracking-widest">Deposit Hub</span>
                        </div>
                        <svg class="relative z-10 w-5 h-5 text-green-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('member.investments.create') }}" class="flex items-center justify-between p-5 bg-purple-50/50 rounded-2xl hover:bg-purple-600 hover:text-white transition-all group overflow-hidden relative">
                         <div class="relative z-10 flex items-center gap-4">
                             <div class="w-10 h-10 bg-white shadow-sm flex items-center justify-center rounded-xl text-purple-600 group-hover:text-purple-600">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                             </div>
                             <span class="text-xs font-black uppercase tracking-widest">Growth Engine</span>
                        </div>
                        <svg class="relative z-10 w-5 h-5 text-purple-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    <a href="{{ route('member.issues.create') }}" class="flex items-center justify-between p-5 bg-orange-50/50 rounded-2xl hover:bg-orange-600 hover:text-white transition-all group overflow-hidden relative">
                         <div class="relative z-10 flex items-center gap-4">
                             <div class="w-10 h-10 bg-white shadow-sm flex items-center justify-center rounded-xl text-orange-600 group-hover:text-orange-600">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                             </div>
                             <span class="text-xs font-black uppercase tracking-widest">Issue Support</span>
                        </div>
                        <svg class="relative z-10 w-5 h-5 text-orange-300 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Priority Alerts -->
            @if($stats['pending_issues'] > 0 || $stats['pending_loans'] > 0)
            <div class="bg-red-600/5 border border-red-600/10 rounded-lg p-6 sm:p-8">
                 <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
                    Priority Alerts
                 </h3>
                 <div class="space-y-4">
                    @if($stats['pending_loans'] > 0)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-red-100">
                            <p class="text-xs font-black text-gray-900 mb-1">Awaiting Funding</p>
                            <p class="text-xs text-gray-400 leading-relaxed font-bold">{{ $stats['pending_loans'] }} credit application(s) undergoing review.</p>
                        </div>
                    @endif
                    @if($stats['pending_issues'] > 0)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-red-100">
                            <p class="text-xs font-black text-gray-900 mb-1">Technical Review</p>
                            <p class="text-xs text-gray-400 leading-relaxed font-bold">You have unresolved tickets requiring your attention.</p>
                        </div>
                    @endif
                 </div>
            </div>
            @endif

            <!-- Special Administrative Roles -->
            @if($isLoanOfficer || $isDepositOfficer || $isInvestmentOfficer || $isChairperson || $isSecretary || $isAccountant)
            <div class="bg-[#015425] rounded-lg p-6 sm:p-8 text-white shadow-lg relative overflow-hidden group">
                 <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
                 <h3 class="text-sm font-black mb-6 border-b border-white/10 pb-4">Governance Mandate</h3>
                 <div class="flex flex-wrap gap-2">
                    @if($isLoanOfficer) <span class="px-3 py-1 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest">Loan Officer</span> @endif
                    @if($isDepositOfficer) <span class="px-3 py-1 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest">Deposit Officer</span> @endif
                    @if($isInvestmentOfficer) <span class="px-3 py-1 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest">Investment Officer</span> @endif
                    @if($isChairperson) <span class="px-3 py-1 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest">Chairperson</span> @endif
                 </div>
                 <p class="text-[10px] text-green-300 mt-6 leading-relaxed opacity-60">As an officer, you have escalated visibility into community assets.</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
