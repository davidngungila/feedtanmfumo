@extends('layouts.admin')

@section('page-title', 'SMS Logs')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Logs & Analytics</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive view of all SMS communications with detailed analytics</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.sms.send') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Send SMS
                </a>
                <a href="{{ route('admin.sms.templates') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Templates
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

        <!-- Enhanced Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-4">
            <!-- SMS Balance -->
            @if($balance)
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-[#015425]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Balance</p>
                        <p class="text-xl font-bold text-[#015425]">{{ number_format($balance['sms_balance'] ?? 0) }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $balance['display'] ?? '' }}</p>
                    </div>
                    <svg class="w-6 h-6 text-[#015425] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            @endif

            <!-- Total Logs -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-xs text-gray-500 uppercase">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>

            <!-- Successful -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <p class="text-xs text-gray-500 uppercase">Success</p>
                <p class="text-xl font-bold text-green-600">{{ number_format($stats['success']) }}</p>
            </div>

            <!-- Failed -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
                <p class="text-xs text-gray-500 uppercase">Failed</p>
                <p class="text-xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
            </div>

            <!-- Today -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <p class="text-xs text-gray-500 uppercase">Today</p>
                <p class="text-xl font-bold text-blue-600">{{ number_format($stats['today']) }}</p>
            </div>

            <!-- This Week -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <p class="text-xs text-gray-500 uppercase">This Week</p>
                <p class="text-xl font-bold text-purple-600">{{ number_format($stats['this_week']) }}</p>
            </div>

            <!-- This Month -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-indigo-500">
                <p class="text-xs text-gray-500 uppercase">This Month</p>
                <p class="text-xl font-bold text-indigo-600">{{ number_format($stats['this_month']) }}</p>
            </div>

            <!-- Delivered -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-400">
                <p class="text-xs text-gray-500 uppercase">Delivered</p>
                <p class="text-xl font-bold text-green-500">{{ number_format($stats['delivered']) }}</p>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                <p class="text-xs text-gray-500 uppercase">Pending</p>
                <p class="text-xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</p>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-400">
                <p class="text-xs text-gray-500 uppercase">Rejected</p>
                <p class="text-xl font-bold text-red-500">{{ number_format($stats['rejected']) }}</p>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Breakdown Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Breakdown</h3>
                <div class="space-y-3">
                    @foreach($statusBreakdown as $status => $count)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $status ?? 'Unknown' }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($count) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ 
                                $status === 'DELIVERED' || $status === 'ACCEPTED' ? 'bg-green-500' : 
                                ($status === 'PENDING' ? 'bg-yellow-500' : 
                                ($status === 'REJECTED' || $status === 'FAILED' ? 'bg-red-500' : 'bg-gray-500')) 
                            }}" style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Channel Breakdown -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Channel Distribution</h3>
                <div class="space-y-3">
                    @foreach($channelBreakdown as $channel => $count)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $channel ?? 'Unknown' }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($count) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-[#015425]" style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Daily Statistics Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Statistics (Last 30 Days)</h3>
            <div class="h-64 flex items-end justify-between gap-1">
                @php
                    $maxCount = $dailyStats->max('count') ?? 1;
                @endphp
                @foreach($dailyStats as $stat)
                <div class="flex-1 flex flex-col items-center group">
                    <div class="w-full bg-gray-200 rounded-t relative" style="height: {{ ($stat->count / $maxCount) * 100 }}%">
                        <div class="absolute inset-0 bg-[#015425] rounded-t"></div>
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap">
                            {{ $stat->date }}: {{ $stat->count }} total, {{ $stat->success_count }} success
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1 transform -rotate-45 origin-top-left whitespace-nowrap" style="writing-mode: vertical-rl;">
                        {{ \Carbon\Carbon::parse($stat->date)->format('M d') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Recipients and Template Usage -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Recipients -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Recipients</h3>
                <div class="space-y-3">
                    @forelse($topRecipients as $recipient)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $recipient->to }}</p>
                            <p class="text-xs text-gray-500">{{ $recipient->count }} messages</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">{{ $recipient->success_count }}</p>
                            <p class="text-xs text-gray-500">success</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No recipient data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Template Usage -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Most Used Templates</h3>
                <div class="space-y-3">
                    @forelse($templateStats as $templateStat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $templateStat->template->template_name ?? 'Unknown Template' }}</p>
                            <p class="text-xs text-gray-500">{{ $templateStat->count }} uses</p>
                        </div>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-[#015425]" style="width: {{ ($templateStat->count / max($templateStats->max('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No template usage data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Filters & Actions</h3>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.sms.logs.export.pdf', request()->query()) }}" 
                       class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('admin.sms.logs.export.excel', request()->query()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <form action="{{ route('admin.sms.logs.sync') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
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

        <!-- Enhanced Logs Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">SMS Logs</h3>
                <span class="text-sm text-gray-500">Showing {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#015425]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Message ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">From</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">To / Recipient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Channel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Template</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Sent By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-600">
                                    {{ Str::limit($log->message_id ?? 'N/A', 12) }}
                                </div>
                                @if($log->reference)
                                <div class="text-xs text-gray-400">Ref: {{ Str::limit($log->reference, 10) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $log->from ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->to }}</div>
                                @if($log->user)
                                <div class="text-xs text-gray-500">{{ $log->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->message)
                                <div class="max-w-xs">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $log->message }}">
                                        {{ Str::limit($log->message, 60) }}
                                    </div>
                                    @if($log->sms_count > 1)
                                    <div class="text-xs text-gray-400 mt-1">{{ $log->sms_count }} SMS</div>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-gray-400 italic">No message available</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->channel ?? 'Internet SMS' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $log->status_badge }}">
                                    {{ $log->status_group_name ?? 'Unknown' }}
                                </span>
                                @if($log->status_name && $log->status_name !== $log->status_group_name)
                                <div class="text-xs text-gray-400 mt-1">{{ $log->status_name }}</div>
                                @endif
                                @if($log->error_message)
                                <div class="text-xs text-red-500 mt-1 truncate max-w-xs" title="{{ $log->error_message }}">
                                    {{ Str::limit($log->error_message, 30) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->template)
                                <div class="max-w-xs">
                                    <div class="font-medium text-gray-900">{{ $log->template->template_name }}</div>
                                    @if($log->saving_behavior)
                                    <div class="text-xs text-gray-400">{{ $log->saving_behavior }}</div>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->sentByUser)
                                <div>{{ $log->sentByUser->name }}</div>
                                <div class="text-xs text-gray-400">{{ $log->sentByUser->email }}</div>
                                @else
                                <span class="text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->sent_at)
                                <div>
                                    <div class="font-medium">{{ $log->sent_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->sent_at->format('H:i:s') }}</div>
                                    @if($log->done_at)
                                    <div class="text-xs text-green-600 mt-1">Done: {{ $log->done_at->format('H:i:s') }}</div>
                                    @endif
                                </div>
                                @else
                                <div>
                                    <div class="font-medium">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.sms.logs.show', $log) }}" 
                                   class="text-[#015425] hover:text-[#027a3a] transition inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">No SMS logs found</p>
                                    <p class="text-xs mt-1">
                                        <form action="{{ route('admin.sms.logs.sync') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[#015425] hover:underline">Sync from API</button>
                                        </form>
                                        to load logs.
                                    </p>
                                </div>
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
