@extends('layouts.admin')

@section('page-title', 'SMS Logs')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Logs</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View and manage all SMS communication logs</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.sms.send') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Send SMS
                </a>
                <a href="{{ route('admin.sms.settings') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Settings
                </a>
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Communication
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Stats and Balance -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- SMS Balance -->
            @if($balance)
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-[#015425]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">SMS Balance</p>
                        <p class="text-2xl font-bold text-[#015425]">{{ number_format($balance['sms_balance'] ?? 0) }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $balance['display'] ?? '' }}</p>
                    </div>
                    <svg class="w-8 h-8 text-[#015425] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            @endif

            <!-- Total Logs -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-xs text-gray-500 uppercase">Total Logs</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>

            <!-- Successful -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <p class="text-xs text-gray-500 uppercase">Successful</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['success']) }}</p>
            </div>

            <!-- Failed -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
                <p class="text-xs text-gray-500 uppercase">Failed</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
            </div>

            <!-- Today -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <p class="text-xs text-gray-500 uppercase">Today</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['today']) }}</p>
            </div>
        </div>

        <!-- Filters and Sync -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Filters</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.sms.logs.export.pdf', request()->query()) }}" 
                       class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium text-sm">
                        Export PDF
                    </a>
                    <a href="{{ route('admin.sms.logs.export.excel', request()->query()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium text-sm">
                        Export Excel
                    </a>
                    <form action="{{ route('admin.sms.logs.sync') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium text-sm">
                            Sync from API
                        </button>
                    </form>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.sms.logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="from" class="block text-sm font-medium text-gray-700 mb-1">From (Sender ID)</label>
                    <input type="text" name="from" id="from" value="{{ request('from') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                </div>

                <div>
                    <label for="to" class="block text-sm font-medium text-gray-700 mb-1">To (Phone Number)</label>
                    <input type="text" name="to" id="to" value="{{ request('to') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                        <option value="">All Status</option>
                        <option value="ACCEPTED" {{ request('status') === 'ACCEPTED' ? 'selected' : '' }}>ACCEPTED</option>
                        <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                        <option value="REJECTED" {{ request('status') === 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                        <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>FAILED</option>
                    </select>
                </div>

                <div>
                    <label for="success" class="block text-sm font-medium text-gray-700 mb-1">Result</label>
                    <select name="success" id="success" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('success') === '1' ? 'selected' : '' }}>Success</option>
                        <option value="0" {{ request('success') === '0' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div>
                    <label for="sent_since" class="block text-sm font-medium text-gray-700 mb-1">Sent Since</label>
                    <input type="date" name="sent_since" id="sent_since" value="{{ request('sent_since') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                </div>

                <div>
                    <label for="sent_until" class="block text-sm font-medium text-gray-700 mb-1">Sent Until</label>
                    <input type="date" name="sent_until" id="sent_until" value="{{ request('sent_until') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                </div>

                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                        Filter
                    </button>
                    <a href="{{ route('admin.sms.logs') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition font-medium">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $log->from ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->to }}
                                @if($log->user)
                                <br><span class="text-xs text-gray-400">{{ $log->user->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="max-w-xs truncate" title="{{ $log->message }}">
                                    {{ Str::limit($log->message, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->channel ?? 'Internet SMS' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $log->status_badge }}">
                                    {{ $log->status_group_name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->sent_at)
                                <div>
                                    <div>Send: {{ $log->sent_at->format('Y-m-d H:i:s') }}</div>
                                    @if($log->done_at)
                                    <div class="text-xs text-gray-400">Done: {{ $log->done_at->format('H:i:s') }}</div>
                                    @endif
                                </div>
                                @else
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.sms.logs.show', $log) }}" 
                                   class="text-[#015425] hover:text-[#027a3a]">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No SMS logs found. 
                                <form action="{{ route('admin.sms.logs.sync') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[#015425] hover:underline">Sync from API</button>
                                </form>
                                to load logs.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

