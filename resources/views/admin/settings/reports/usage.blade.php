@extends('layouts.admin')

@section('page-title', 'Usage Statistics')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Usage Statistics</h1>
                <p class="text-white text-opacity-90">Comprehensive system usage analytics and user activity</p>
            </div>
            <a href="{{ route('admin.system-settings.usage-statistics.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Total Users</div>
            <div class="text-3xl font-bold text-[#015425]">{{ number_format($stats['total_users']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Active Users</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['active_users']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Logins Today</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_logins_today']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Audit Logs</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_audit_logs']) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Logins This Week</div>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_logins_this_week']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Logins This Month</div>
            <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_logins_this_month']) }}</div>
        </div>
    </div>

    <!-- Daily Login Trends -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Daily Login Trends (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Login Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dailyLogins as $day)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($day->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No login data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Active Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Top Active Users</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Total Logins</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topActiveUsers as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($user->login_count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No user data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Login Sessions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Login Sessions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Login At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Logout At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentLogins as $login)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $login->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $login->ip_address ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($login->user_agent ?? 'N/A', 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $login->login_at ? \Carbon\Carbon::parse($login->login_at)->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $login->logout_at ? \Carbon\Carbon::parse($login->logout_at)->format('M d, Y H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $login->logout_at ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                {{ $login->logout_at ? 'Logged Out' : 'Active' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No login sessions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Activity Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">User Activity Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">User Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Total Logins</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Last Login</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($userActivityBreakdown as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($user->total_logins) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('M d, Y H:i') : 'Never' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No user activity data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $userActivityBreakdown->links() }}
        </div>
    </div>

    <!-- Monthly Registration Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Monthly User Registration Trend (Last 12 Months)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">New Users</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($monthlyRegistrations as $month)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#015425]">{{ number_format($month->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No registration data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
