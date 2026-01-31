@extends('layouts.admin')

@section('page-title', 'Performance Monitoring')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Performance Monitoring</h1>
                <p class="text-white text-opacity-90">Comprehensive system performance metrics and activity monitoring</p>
            </div>
            <a href="{{ route('admin.system-settings.performance-monitoring.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Performance Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Memory Usage</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($performanceMetrics['memory_usage'], 2) }} MB</div>
                <div class="text-xs text-gray-500 mt-1">Current</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Peak Memory</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($performanceMetrics['peak_memory'], 2) }} MB</div>
                <div class="text-xs text-gray-500 mt-1">Maximum</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Memory Limit</div>
                <div class="text-2xl font-bold text-purple-600">{{ $performanceMetrics['memory_limit'] }}</div>
                <div class="text-xs text-gray-500 mt-1">PHP Configuration</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Max Execution Time</div>
                <div class="text-2xl font-bold text-orange-600">{{ $performanceMetrics['max_execution_time'] }}s</div>
                <div class="text-xs text-gray-500 mt-1">PHP Configuration</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">PHP Version</div>
                <div class="text-2xl font-bold text-indigo-600">{{ $performanceMetrics['php_version'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Server</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Laravel Version</div>
                <div class="text-2xl font-bold text-pink-600">{{ $performanceMetrics['laravel_version'] }}</div>
                <div class="text-xs text-gray-500 mt-1">Framework</div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Activity Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Activities Today</div>
                <div class="text-2xl font-bold text-[#015425]">{{ number_format($dbStats['total_activities_today']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Activities This Week</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($dbStats['total_activities_this_week']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Activities This Month</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($dbStats['total_activities_this_month']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Activities</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($recentActivities->count()) }}</div>
            </div>
        </div>
    </div>

    <!-- Activity by Type -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Activity Breakdown by Type (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Action Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activityByType as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ucfirst($activity->action ?? 'N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($activity->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No activity data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activity by User -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Top Active Users (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Activity Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activityByUser as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->user_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($user->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No user activity data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Activity Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Daily Activity Trend (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Activity Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dailyActivityTrend as $day)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($day->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No activity trend data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Recent System Activities</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentActivities as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $activity->user_name ?? 'System' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($activity->action ?? 'N/A') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($activity->description ?? 'N/A', 60) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No recent activities found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
