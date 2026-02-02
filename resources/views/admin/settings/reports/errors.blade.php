@extends('layouts.admin')

@section('page-title', 'Error Reports')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Error Reports</h1>
                <p class="text-white text-opacity-90">Comprehensive system error tracking and analysis</p>
            </div>
            <a href="{{ route('admin.system-settings.error-reports.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Total Errors</div>
            <div class="text-3xl font-bold text-red-600">{{ number_format($stats['total_errors']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Errors Today</div>
            <div class="text-3xl font-bold text-orange-600">{{ number_format($stats['errors_today']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Errors This Week</div>
            <div class="text-3xl font-bold text-yellow-600">{{ number_format($stats['errors_this_week']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Errors This Month</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['errors_this_month']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Filter Errors</h2>
        <form method="GET" action="{{ route('admin.system-settings.error-reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Queue</label>
                <input type="text" name="queue" value="{{ request('queue') }}" placeholder="Queue name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#015425] text-white px-4 py-2 rounded-md font-semibold hover:bg-[#027a3a] transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Errors by Queue -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Error Breakdown by Queue</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Queue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Error Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($errorsByQueue as $queue)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->queue ?? 'default' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ number_format($queue->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No queue data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Error Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Daily Error Trend (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Error Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dailyErrorTrend as $day)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ number_format($day->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No error trend data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Most Common Exceptions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Most Common Exceptions (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Exception</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Occurrences</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commonExceptions as $exception)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ Str::limit($exception->exception ?? 'N/A', 80) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ number_format($exception->count) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No exception data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Failed Job Errors -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Failed Job Errors</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Queue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Exception</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Failed At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($errors as $error)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">#{{ $error->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $error->queue ?? 'default' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ Str::limit($error->exception ?? 'N/A', 80) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $error->failed_at ? \Carbon\Carbon::parse($error->failed_at)->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No errors found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $errors->links() }}
        </div>
    </div>
</div>
@endsection
