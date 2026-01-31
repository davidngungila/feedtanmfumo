@extends('layouts.admin')

@section('page-title', 'System Reports')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Reports</h1>
                <p class="text-white text-opacity-90">Comprehensive system overview and statistics</p>
            </div>
            <a href="{{ route('admin.system-settings.system-reports.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">System Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Laravel Version</div>
                <div class="text-lg font-bold text-[#015425]">{{ $systemInfo['laravel_version'] }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">PHP Version</div>
                <div class="text-lg font-bold text-[#015425]">{{ $systemInfo['php_version'] }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Environment</div>
                <div class="text-lg font-bold">
                    <span class="px-2 py-1 rounded-full text-sm {{ $systemInfo['environment'] === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($systemInfo['environment']) }}
                    </span>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Database Driver</div>
                <div class="text-lg font-bold text-[#015425]">{{ ucfirst($systemInfo['database_driver']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Timezone</div>
                <div class="text-lg font-bold text-[#015425]">{{ $systemInfo['timezone'] }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Server Time</div>
                <div class="text-lg font-bold text-[#015425]">{{ $systemInfo['server_time'] }}</div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">User Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Users</div>
                <div class="text-3xl font-bold text-[#015425]">{{ number_format($userStats['total_users']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Active Users</div>
                <div class="text-3xl font-bold text-green-600">{{ number_format($userStats['active_users']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Members</div>
                <div class="text-3xl font-bold text-blue-600">{{ number_format($userStats['members']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Administrators</div>
                <div class="text-3xl font-bold text-purple-600">{{ number_format($userStats['admins']) }}</div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">New Users Today</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($userStats['new_users_today']) }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">New Users This Week</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($userStats['new_users_this_week']) }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">New Users This Month</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($userStats['new_users_this_month']) }}</div>
            </div>
        </div>
    </div>

    <!-- Login Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Login Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Logins</div>
                <div class="text-2xl font-bold text-[#015425]">{{ number_format($loginStats['total_logins']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Logins Today</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($loginStats['logins_today']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Logins This Week</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($loginStats['logins_this_week']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Logins This Month</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($loginStats['logins_this_month']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Active Sessions</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($loginStats['active_sessions']) }}</div>
            </div>
        </div>
    </div>

    <!-- Database Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Database Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Loans</div>
                <div class="text-2xl font-bold text-[#015425]">{{ number_format($dbStats['total_loans']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Savings Accounts</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($dbStats['total_savings_accounts']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Investments</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($dbStats['total_investments']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Transactions</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($dbStats['total_transactions']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Welfare Records</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($dbStats['total_welfare_records']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Issues</div>
                <div class="text-2xl font-bold text-red-600">{{ number_format($dbStats['total_issues']) }}</div>
            </div>
        </div>
    </div>

    <!-- Audit & Activity Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Audit & Activity Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Audit Logs</div>
                <div class="text-2xl font-bold text-[#015425]">{{ number_format($auditStats['total_audit_logs']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Audit Logs Today</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($auditStats['audit_logs_today']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Audit Logs This Week</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($auditStats['audit_logs_this_week']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Activity Logs</div>
                <div class="text-2xl font-bold text-purple-600">{{ number_format($auditStats['total_activity_logs']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Activity Logs Today</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($auditStats['activity_logs_today']) }}</div>
            </div>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Error Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Total Errors</div>
                <div class="text-2xl font-bold text-red-600">{{ number_format($errorStats['total_errors']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Errors Today</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($errorStats['errors_today']) }}</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="text-sm font-medium text-gray-600 mb-1">Errors This Week</div>
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($errorStats['errors_this_week']) }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Logins -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Login Sessions</h2>
            <div class="space-y-3">
                @forelse($recentLogins as $login)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $login->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-600">{{ $login->ip_address ?? 'N/A' }}</div>
                        </div>
                        <div class="text-xs text-gray-500 text-right">
                            {{ $login->login_at ? \Carbon\Carbon::parse($login->login_at)->format('M d, H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent logins</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Audit Logs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Audit Logs</h2>
            <div class="space-y-3">
                @forelse($recentAudits as $audit)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold text-gray-900">{{ ucfirst($audit->action ?? 'N/A') }}</div>
                            <div class="text-sm text-gray-600">{{ $audit->model_type ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $audit->user_name ?? 'System' }}</div>
                        </div>
                        <div class="text-xs text-gray-500 text-right">
                            {{ $audit->created_at ? \Carbon\Carbon::parse($audit->created_at)->format('M d, H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent audit logs</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Activities</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-semibold text-gray-900">{{ ucfirst($activity->action ?? 'N/A') }}</div>
                            <div class="text-sm text-gray-600">{{ Str::limit($activity->description ?? 'N/A', 40) }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->user_name ?? 'System' }}</div>
                        </div>
                        <div class="text-xs text-gray-500 text-right">
                            {{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->format('M d, H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent activities</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
