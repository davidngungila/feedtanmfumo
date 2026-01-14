@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('page-title', 'Resolution Status')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Resolution Status</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Track and analyze resolved issues and resolution metrics</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Resolved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['total_resolved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Resolved This Month</p>
                    <p class="text-2xl font-bold text-[#015425] mt-1">{{ $stats['resolved_this_month'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#015425]/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Resolved Last Month</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['resolved_last_month'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg Resolution Time</p>
                    @if($stats['avg_resolution_days'])
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($stats['avg_resolution_days'], 1) }} days</p>
                    @else
                        <p class="text-lg font-bold text-gray-400 mt-1">N/A</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolution by Category -->
    @if($stats['by_category']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resolved Issues by Category</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($stats['by_category'] as $category => $count)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">{{ ucfirst($category) }}</p>
                    <p class="text-2xl font-bold text-[#015425] mt-1">{{ $count }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Resolution by Resolver -->
    @if($stats['by_resolver']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resolved Issues by Staff Member</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($stats['by_resolver'] as $resolver => $count)
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">{{ $resolver }}</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $count }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.issues.resolution-status') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or issue number..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Categories</option>
                        <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                        <option value="suggestion" {{ request('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                        <option value="inquiry" {{ request('category') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                        <option value="request" {{ request('category') == 'request' ? 'selected' : '' }}>Request</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Resolved By Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resolved By</label>
                    <select name="resolved_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Staff</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ request('resolved_by') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Resolved From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resolved From</label>
                    <input type="date" name="resolved_from" value="{{ request('resolved_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>

                <!-- Resolved To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resolved To</label>
                    <input type="date" name="resolved_to" value="{{ request('resolved_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('admin.issues.resolution-status') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Clear Filters
                </a>
                <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Resolved Issues Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved At</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolution Time</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $issue->issue_number }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($issue->title, 50) }}</div>
                                <div class="text-xs text-gray-500">By: {{ $issue->user->name }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($issue->category) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $issue->resolvedBy->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($issue->resolved_at)
                                    {{ $issue->resolved_at->format('M d, Y') }}<br>
                                    <span class="text-xs">{{ $issue->resolved_at->format('h:i A') }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($issue->resolved_at)
                                    @php
                                        $hours = $issue->created_at->diffInHours($issue->resolved_at);
                                        $days = floor($hours / 24);
                                        $remainingHours = $hours % 24;
                                    @endphp
                                    @if($days > 0)
                                        {{ $days }}d {{ $remainingHours }}h
                                    @else
                                        {{ $remainingHours }}h
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.issues.show', $issue) }}" class="text-[#015425] hover:text-[#013019]" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No resolved issues found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($issues->hasPages())
            <div class="px-4 py-4 border-t border-gray-200">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

