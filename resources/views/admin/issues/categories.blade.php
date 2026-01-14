@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('page-title', 'Issue Categories')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Issue Categories</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Browse and manage issues by category (Login, Transactions, Reports, etc.)</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.issues.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Report New Issue
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-sm text-gray-600">Total Issues</p>
            <p class="text-2xl font-bold text-[#015425] mt-1">{{ $overallStats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $overallStats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-sm text-gray-600">In Progress</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $overallStats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-sm text-gray-600">Resolved</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $overallStats['resolved'] }}</p>
        </div>
    </div>

    <!-- Category Stats Grid -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Issues by Category</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($statsByCategory as $key => $stat)
                <a href="{{ route('admin.issues.categories', ['category' => $key]) }}" 
                   class="block p-4 rounded-lg border-2 transition {{ $selectedCategory == $key ? 'border-[#015425] bg-[#015425]/5' : 'border-gray-200 hover:border-[#015425]/50 hover:bg-gray-50' }}">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-900">{{ $stat['label'] }}</h4>
                        @if($selectedCategory == $key)
                            <svg class="w-5 h-5 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-semibold text-gray-900">{{ $stat['total'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-yellow-600">Pending:</span>
                            <span class="font-semibold text-yellow-600">{{ $stat['pending'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-blue-600">In Progress:</span>
                            <span class="font-semibold text-blue-600">{{ $stat['in_progress'] }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-green-600">Resolved:</span>
                            <span class="font-semibold text-green-600">{{ $stat['resolved'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.issues.categories') }}" class="space-y-4">
            <input type="hidden" name="category" value="{{ $selectedCategory }}">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or issue number..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <div class="flex space-x-2 w-full">
                        <a href="{{ route('admin.issues.categories', ['category' => $selectedCategory]) }}" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                            Clear Filters
                        </a>
                        <button type="submit" class="flex-1 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Issues Table -->
    @if($selectedCategory !== 'all')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-800">
                    Showing issues for category: <strong>{{ $statsByCategory[$selectedCategory]['label'] }}</strong>
                    (<a href="{{ route('admin.issues.categories') }}" class="underline hover:text-blue-900">View all categories</a>)
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
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
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'urgent' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $priorityColors[$issue->priority] ?? $priorityColors['low'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                        'closed' => 'bg-gray-100 text-gray-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusColor = $statusColors[$issue->status] ?? $statusColors['pending'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $issue->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.issues.show', $issue) }}" class="text-[#015425] hover:text-[#013019]" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.issues.edit', $issue) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No issues found
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

