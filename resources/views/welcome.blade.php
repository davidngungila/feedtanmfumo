@extends('layouts.app')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-gradient-emaoni">FEEDTAN DIGITAL</h1>
                    </div>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ url('/') }}" class="px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                            Home
                        </a>
                        <a href="#statistics" class="px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                            Statistics
                        </a>
                        <a href="#services" class="px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                            Services
                        </a>
                        <a href="#features" class="px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                            Features
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-[#015425] hover:bg-green-50 rounded-md transition hidden md:block">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-[#015425] hover:bg-green-50 rounded-md transition hidden md:block">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition hidden md:block">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden p-2 text-[#015425] hover:bg-green-50 rounded-md transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200">
                <div class="px-2 pt-2 space-y-1">
                    <a href="{{ url('/') }}" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                        Home
                    </a>
                    <a href="#statistics" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                        Statistics
                    </a>
                    <a href="#services" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                        Services
                    </a>
                    <a href="#features" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition font-medium">
                        Features
                    </a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-[#015425] hover:bg-green-50 rounded-md transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-3 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-gradient-emaoni mb-6 animate-fade-in">
                FEEDTAN DIGITAL Platform
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-3xl mx-auto">
                Community Feedback & Microfinance Management Platform
            </p>
            <p class="text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
                Empowering FeedTan Community members with accessible, efficient, and transparent platform for voicing concerns, accessing services, and participating in community governance.
            </p>

            <!-- Key Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto mb-16">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="text-3xl font-bold text-[#015425] mb-2">{{ number_format($stats['total_members']) }}</div>
                    <div class="text-sm text-gray-600">Total Members</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($stats['total_loans']) }}</div>
                    <div class="text-sm text-gray-600">Total Loans</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="text-3xl font-bold text-green-600 mb-2">TZS {{ number_format($stats['total_savings_balance'] / 1000, 0) }}K</div>
                    <div class="text-sm text-gray-600">Total Savings</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($stats['total_investments']) }}</div>
                    <div class="text-sm text-gray-600">Investments</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <section id="statistics" class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Platform Statistics</h2>
            
            <!-- Loan Statistics -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-[#015425] mb-6">Loan Portfolio</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-blue-700 mb-2">{{ number_format($loanStats['active']) }}</div>
                        <div class="text-sm text-blue-600 font-medium">Active Loans</div>
                        <div class="text-xs text-gray-600 mt-1">TZS {{ number_format($loanStats['outstanding'] / 1000, 0) }}K Outstanding</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-green-700 mb-2">{{ number_format($loanStats['completed']) }}</div>
                        <div class="text-sm text-green-600 font-medium">Completed Loans</div>
                        <div class="text-xs text-gray-600 mt-1">{{ $successRate }}% Success Rate</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-yellow-700 mb-2">{{ number_format($loanStats['pending']) }}</div>
                        <div class="text-sm text-yellow-600 font-medium">Pending Approval</div>
                        <div class="text-xs text-gray-600 mt-1">Awaiting Review</div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-red-700 mb-2">{{ number_format($loanStats['overdue']) }}</div>
                        <div class="text-sm text-red-600 font-medium">Overdue Loans</div>
                        <div class="text-xs text-gray-600 mt-1">Requires Attention</div>
                    </div>
                </div>
                <div class="mt-6 grid md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                        <div class="text-lg font-semibold text-gray-700 mb-1">Total Disbursed</div>
                        <div class="text-2xl font-bold text-blue-600">TZS {{ number_format($loanStats['total_disbursed'] / 1000, 0) }}K</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                        <div class="text-lg font-semibold text-gray-700 mb-1">Total Repaid</div>
                        <div class="text-2xl font-bold text-green-600">TZS {{ number_format($loanStats['total_repaid'] / 1000, 0) }}K</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                        <div class="text-lg font-semibold text-gray-700 mb-1">Recovery Rate</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $portfolioHealth['recovery_rate'] }}%</div>
                    </div>
                </div>
            </div>

            <!-- Savings Statistics -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-[#015425] mb-6">Savings Accounts</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-emerald-700 mb-2">{{ number_format($savingsStats['active_accounts']) }}</div>
                        <div class="text-sm text-emerald-600 font-medium">Active Accounts</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-teal-700 mb-2">TZS {{ number_format($savingsStats['total_balance'] / 1000, 0) }}K</div>
                        <div class="text-sm text-teal-600 font-medium">Total Balance</div>
                    </div>
                    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-cyan-700 mb-2">TZS {{ number_format($savingsStats['average_balance'] / 1000, 0) }}K</div>
                        <div class="text-sm text-cyan-600 font-medium">Average Balance</div>
                    </div>
                </div>
            </div>

            <!-- Investment Statistics -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-[#015425] mb-6">Investment Portfolio</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-indigo-700 mb-2">{{ number_format($investmentStats['active']) }}</div>
                        <div class="text-sm text-indigo-600 font-medium">Active Investments</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-purple-700 mb-2">TZS {{ number_format($investmentStats['total_principal'] / 1000, 0) }}K</div>
                        <div class="text-sm text-purple-600 font-medium">Total Principal</div>
                    </div>
                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-pink-700 mb-2">TZS {{ number_format($investmentStats['total_profit'] / 1000, 0) }}K</div>
                        <div class="text-sm text-pink-600 font-medium">Total Profit</div>
                    </div>
                    <div class="bg-gradient-to-br from-rose-50 to-rose-100 p-6 rounded-lg shadow-md">
                        <div class="text-2xl font-bold text-rose-700 mb-2">{{ number_format($investmentStats['matured']) }}</div>
                        <div class="text-sm text-rose-600 font-medium">Matured Plans</div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <!-- Monthly Trends Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Monthly Trends (Last 6 Months)</h4>
                    <canvas id="monthlyTrendsChart" height="300"></canvas>
                </div>

                <!-- Service Distribution Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Service Distribution</h4>
                    <canvas id="serviceDistributionChart" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold text-[#015425] mb-6">Recent Activity (Last 30 Days)</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($recentActivity['new_members']) }}</div>
                        <div class="text-sm text-gray-600">New Members</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($recentActivity['new_loans']) }}</div>
                        <div class="text-sm text-gray-600">New Loans</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($recentActivity['new_savings']) }}</div>
                        <div class="text-sm text-gray-600">New Savings</div>
                    </div>
                    <div class="text-center p-4 bg-pink-50 rounded-lg">
                        <div class="text-3xl font-bold text-pink-600 mb-2">{{ number_format($recentActivity['new_investments']) }}</div>
                        <div class="text-sm text-gray-600">New Investments</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="bg-gradient-to-br from-green-50 to-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Our Services</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-3 text-center">Loans</h3>
                    <p class="text-gray-600 text-center mb-4">Accessible loan products with flexible repayment terms</p>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($serviceDistribution['loans']) }}</div>
                        <div class="text-sm text-gray-500">Total Loans</div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-3 text-center">Savings</h3>
                    <p class="text-gray-600 text-center mb-4">Secure savings accounts with competitive interest rates</p>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($serviceDistribution['savings']) }}</div>
                        <div class="text-sm text-gray-500">Total Accounts</div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-3 text-center">Investments</h3>
                    <p class="text-gray-600 text-center mb-4">Long-term investment plans with attractive returns</p>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($serviceDistribution['investments']) }}</div>
                        <div class="text-sm text-gray-500">Active Plans</div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-3 text-center">Social Welfare</h3>
                    <p class="text-gray-600 text-center mb-4">Community support and welfare benefits</p>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-pink-600">{{ number_format($serviceDistribution['welfare']) }}</div>
                        <div class="text-sm text-gray-500">Total Records</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Platform Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">💬</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Feedback System</h4>
                    <p class="text-sm text-gray-600">Submit complaints, suggestions, and inquiries</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">📱</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Multi-Channel</h4>
                    <p class="text-sm text-gray-600">Web, Mobile App, USSD (*152*00#), SMS, WhatsApp</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">🔒</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Secure & Safe</h4>
                    <p class="text-sm text-gray-600">End-to-end encryption and data protection</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">📈</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Analytics</h4>
                    <p class="text-sm text-gray-600">Track performance and insights</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">⚡</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Fast Processing</h4>
                    <p class="text-sm text-gray-600">Quick loan approvals and transactions</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">📊</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Real-time Reports</h4>
                    <p class="text-sm text-gray-600">Live updates and comprehensive reporting</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">🎯</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Goal Tracking</h4>
                    <p class="text-sm text-gray-600">Set and monitor financial goals</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-[#015425] rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">🤝</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Community Support</h4>
                    <p class="text-sm text-gray-600">Connect with community members</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-[#015425] text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl mb-8 opacity-90">
                Join the FeedTan Community and start managing your microfinance activities today.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-white text-[#015425] rounded-md font-semibold hover:bg-gray-100 transition">
                        Get Started
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-transparent border-2 border-white text-white rounded-md font-semibold hover:bg-white hover:text-[#015425] transition">
                        Log In
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">FEEDTAN DIGITAL</h3>
                    <p class="text-gray-400 text-sm">
                        Empowering communities through accessible microfinance services.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Loans</a></li>
                        <li><a href="#" class="hover:text-white transition">Savings</a></li>
                        <li><a href="#" class="hover:text-white transition">Investments</a></li>
                        <li><a href="#" class="hover:text-white transition">Social Welfare</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#statistics" class="hover:text-white transition">Statistics</a></li>
                        <li><a href="#services" class="hover:text-white transition">Services</a></li>
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Email: info@feedtan.com</li>
                        <li>Phone: +255 XXX XXX XXX</li>
                        <li>USSD: *152*00#</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} FeedTan Community Microfinance Group. All rights reserved.</p>
                <p class="text-gray-500 text-sm mt-2">FEEDTAN DIGITAL Platform v1.0</p>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart');
    if (monthlyTrendsCtx) {
        new Chart(monthlyTrendsCtx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyTrends, 'month_short')),
                datasets: [
                    {
                        label: 'Members',
                        data: @json(array_column($monthlyTrends, 'members')),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Loans',
                        data: @json(array_column($monthlyTrends, 'loans')),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Savings',
                        data: @json(array_column($monthlyTrends, 'savings')),
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Service Distribution Chart
    const serviceDistributionCtx = document.getElementById('serviceDistributionChart');
    if (serviceDistributionCtx) {
        new Chart(serviceDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Loans', 'Savings', 'Investments', 'Welfare'],
                datasets: [{
                    data: [
                        {{ $serviceDistribution['loans'] }},
                        {{ $serviceDistribution['savings'] }},
                        {{ $serviceDistribution['investments'] }},
                        {{ $serviceDistribution['welfare'] }}
                    ],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush
@endsection
