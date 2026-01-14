@extends('layouts.admin')

@section('page-title', 'Dashboard')


@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Here's what's happening with your platform today.</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('l, F d, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>{{ $stats['total_users'] }} Total Users</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div id="quick-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Users</p>
                    <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total_users']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-600 font-medium">+{{ $stats['new_users_today'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">today</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Issues -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending Issues</p>
                    <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($issues['pending']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-red-600 font-medium">{{ $issues['urgent'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">urgent</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Loans</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($loans['active']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600">{{ $loans['pending'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">pending</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Savings -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Savings</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600">TZS {{ number_format($savings['total_balance'] / 1000, 1) }}K</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-600">{{ $savings['active_accounts'] }}</span>
                        <span class="text-xs text-gray-500 ml-1">active accounts</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">New This Week</p>
                    <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ $stats['new_users_week'] }}</p>
                    @php
                        $weekGrowth = isset($stats['new_users_last_week']) && $stats['new_users_last_week'] > 0 
                            ? round((($stats['new_users_week'] - $stats['new_users_last_week']) / $stats['new_users_last_week']) * 100)
                            : ($stats['new_users_week'] > 0 ? 100 : 0);
                    @endphp
                    <p class="text-xs {{ $weekGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ $weekGrowth >= 0 ? '↑' : '↓' }} {{ abs($weekGrowth) }}% from last week
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Issues Resolved</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $issues['resolved'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $issues['total'] > 0 ? round(($issues['resolved'] / $issues['total']) * 100) : 0 }}% resolution rate
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue Loans</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ $loans['overdue'] }}</p>
                    <p class="text-xs text-red-600 mt-1">Requires attention</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Investments</p>
                    <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ $investments['total'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $investments['active'] }} active</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Users by Role Pie Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Users by Role</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="usersByRoleChart"></canvas>
            </div>
        </div>

        <!-- Issues by Status Pie Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Issues by Status</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="issuesByStatusChart"></canvas>
            </div>
        </div>

        <!-- Loans by Status Pie Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Loans by Status</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="loansByStatusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trends Line Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Monthly Trends (Last 6 Months)</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="monthlyTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Performance Metrics -->
        <div id="performance-metrics" class="lg:col-span-2 bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-[#015425]">Performance Metrics</h3>
                <span class="text-xs sm:text-sm text-gray-500">Last 6 months</span>
            </div>
            <div class="space-y-4">
                @foreach($performanceMetrics as $metric)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs sm:text-sm font-medium text-gray-700">{{ $metric['month'] }}</span>
                        <div class="flex items-center gap-4 text-xs sm:text-sm text-gray-600">
                            <span>Users: {{ $metric['users'] }}</span>
                            <span>Loans: {{ $metric['loans'] }}</span>
                            <span>Issues: {{ $metric['issues'] }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $metric['users'] > 0 ? min(($metric['users'] / max(collect($performanceMetrics)->max('users'), 1)) * 100, 100) : 0 }}%"></div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $metric['loans'] > 0 ? min(($metric['loans'] / max(collect($performanceMetrics)->max('loans'), 1)) * 100, 100) : 0 }}%"></div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 rounded-full" style="width: {{ $metric['issues'] > 0 ? min(($metric['issues'] / max(collect($performanceMetrics)->max('issues'), 1)) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Alerts & Notifications -->
        <div id="alerts" class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-[#015425]">Alerts & Notifications</h3>
                <span class="text-xs text-gray-500">{{ count($alerts) }} active</span>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($alerts as $alert)
                <div class="p-3 rounded-lg border-l-4 
                    {{ $alert['type'] === 'danger' ? 'bg-red-50 border-red-500' : '' }}
                    {{ $alert['type'] === 'warning' ? 'bg-yellow-50 border-yellow-500' : '' }}
                    {{ $alert['type'] === 'info' ? 'bg-blue-50 border-blue-500' : '' }}
                    {{ $alert['type'] === 'success' ? 'bg-green-50 border-green-500' : '' }}">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mt-0.5 mr-2 
                            {{ $alert['type'] === 'danger' ? 'text-red-600' : '' }}
                            {{ $alert['type'] === 'warning' ? 'text-yellow-600' : '' }}
                            {{ $alert['type'] === 'info' ? 'text-blue-600' : '' }}
                            {{ $alert['type'] === 'success' ? 'text-green-600' : '' }}" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-semibold text-gray-900">{{ $alert['title'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $alert['description'] }}</p>
                            @if(isset($alert['action']))
                            <a href="{{ $alert['action'] }}" class="text-xs text-[#015425] hover:underline mt-1 inline-block">View Details →</a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No active alerts</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity & Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div id="recent-activity" class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-[#015425]">Recent Activity</h3>
                <a href="{{ route('admin.issues.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        {{ $activity['color'] === 'blue' ? 'bg-blue-100' : '' }}
                        {{ $activity['color'] === 'red' ? 'bg-red-100' : '' }}
                        {{ $activity['color'] === 'orange' ? 'bg-orange-100' : '' }}
                        {{ $activity['color'] === 'green' ? 'bg-green-100' : '' }}">
                        @if($activity['icon'] === 'user')
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        @elseif($activity['icon'] === 'alert')
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        @elseif($activity['icon'] === 'money')
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-8">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-[#015425]">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs sm:text-sm text-[#015425] hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stats['recent_users'] as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white text-xs font-bold mr-2">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs sm:text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 truncate max-w-[120px]">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($user->roles->count() > 0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($user->role ?? 'Member') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">{{ $user->created_at->format('M d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-8 text-center text-xs sm:text-sm text-gray-500">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions & System Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-[#015425] mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#015425] hover:bg-green-50 transition">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#015425] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 text-center">Add User</span>
                </a>
                <a href="{{ route('admin.issues.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#015425] hover:bg-green-50 transition">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#015425] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 text-center">New Issue</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#015425] hover:bg-green-50 transition">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#015425] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 text-center">Reports</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-[#015425] hover:bg-green-50 transition">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#015425] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium text-gray-700 text-center">Settings</span>
                </a>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-[#015425] mb-4">System Information</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">Laravel Version</span>
                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">PHP Version</span>
                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">Environment</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ app()->environment() === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst(app()->environment()) }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">Server Time</span>
                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ now()->format('H:i:s') }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-xs sm:text-sm text-gray-600">Today's Date</span>
                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.font.family = 'Quicksand, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.position = 'bottom';
    Chart.defaults.plugins.legend.labels.boxWidth = 12;
    Chart.defaults.plugins.legend.labels.padding = 10;

    const chartColors = {
        primary: '#015425',
        primaryLight: '#027a3a',
        primaryDark: '#013019',
        blue: '#3B82F6',
        green: '#10B981',
        yellow: '#F59E0B',
        orange: '#F97316',
        red: '#EF4444',
        purple: '#8B5CF6',
        indigo: '#6366F1',
        pink: '#EC4899',
        teal: '#14B8A6',
        cyan: '#06B6D4',
    };

    // Users by Role Pie Chart
    const usersByRoleCtx = document.getElementById('usersByRoleChart').getContext('2d');
    new Chart(usersByRoleCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($usersByRole)) !!},
            datasets: [{
                data: {!! json_encode(array_values($usersByRole)) !!},
                backgroundColor: [
                    chartColors.primary,
                    chartColors.blue,
                    chartColors.green,
                    chartColors.yellow,
                    chartColors.orange,
                    chartColors.purple,
                    chartColors.indigo,
                    chartColors.pink,
                    chartColors.teal,
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Issues by Status Pie Chart
    const issuesByStatusCtx = document.getElementById('issuesByStatusChart').getContext('2d');
    new Chart(issuesByStatusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($issuesByStatus)) !!},
            datasets: [{
                data: {!! json_encode(array_values($issuesByStatus)) !!},
                backgroundColor: [
                    chartColors.yellow,
                    chartColors.blue,
                    chartColors.green,
                    chartColors.gray || '#6B7280',
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Loans by Status Pie Chart
    const loansByStatusCtx = document.getElementById('loansByStatusChart').getContext('2d');
    new Chart(loansByStatusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($loansByStatus)) !!},
            datasets: [{
                data: {!! json_encode(array_values($loansByStatus)) !!},
                backgroundColor: [
                    chartColors.yellow,
                    chartColors.green,
                    chartColors.blue,
                    chartColors.primary,
                    chartColors.red,
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Monthly Trends Line Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyTrendsData = {!! json_encode($monthlyTrends) !!};
    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: monthlyTrendsData.map(item => item.month),
            datasets: [
                {
                    label: 'Users',
                    data: monthlyTrendsData.map(item => item.users),
                    borderColor: chartColors.blue,
                    backgroundColor: chartColors.blue + '20',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Loans',
                    data: monthlyTrendsData.map(item => item.loans),
                    borderColor: chartColors.green,
                    backgroundColor: chartColors.green + '20',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Issues',
                    data: monthlyTrendsData.map(item => item.issues),
                    borderColor: chartColors.orange,
                    backgroundColor: chartColors.orange + '20',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Savings Accounts',
                    data: monthlyTrendsData.map(item => item.savings),
                    borderColor: chartColors.purple,
                    backgroundColor: chartColors.purple + '20',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>
@endpush
@endsection
