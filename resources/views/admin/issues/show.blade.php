@extends('layouts.admin')

@section('page-title', 'Issue Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Issue Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Issue #{{ $issue->issue_number }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.issues.edit', $issue) }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Edit Issue
                </a>
                <a href="{{ route('admin.issues.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-[#015425]">{{ $issue->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Issue #{{ $issue->issue_number }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 text-xs rounded-full {{ 
                            $issue->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                            ($issue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                            ($issue->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                        }}">
                            {{ ucfirst($issue->priority) }} Priority
                        </span>
                        <span class="px-3 py-1 text-xs rounded-full {{ 
                            $issue->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                            ($issue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                            ($issue->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                        </span>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $issue->description }}</p>
                </div>
            </div>

            @if($issue->resolution)
            <div class="bg-green-50 rounded-lg shadow-md p-6 border border-green-200">
                <h4 class="font-semibold text-green-800 mb-2">Resolution</h4>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $issue->resolution }}</p>
                @if($issue->resolvedBy)
                    <p class="text-sm text-gray-500 mt-3">Resolved by {{ $issue->resolvedBy->name }} on {{ $issue->resolved_at->format('M d, Y') }}</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="font-semibold text-[#015425] mb-4">Issue Information</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Category</p>
                        <p class="font-semibold">{{ ucfirst($issue->category) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Submitted By</p>
                        <p class="font-semibold">{{ $issue->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $issue->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Assigned To</p>
                        <p class="font-semibold">{{ $issue->assignedTo->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-semibold">{{ $issue->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($issue->updated_at != $issue->created_at)
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-semibold">{{ $issue->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if(!$issue->resolution)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="font-semibold text-[#015425] mb-4">Quick Actions</h4>
                <form action="{{ route('admin.issues.update', $issue) }}" method="POST" class="space-y-2">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="title" value="{{ $issue->title }}">
                    <input type="hidden" name="description" value="{{ $issue->description }}">
                    <input type="hidden" name="category" value="{{ $issue->category }}">
                    <input type="hidden" name="priority" value="{{ $issue->priority }}">
                    <input type="hidden" name="assigned_to" value="{{ $issue->assigned_to }}">
                    
                    <button type="submit" name="status" value="in_progress" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                        Mark In Progress
                    </button>
                    <button type="submit" name="status" value="resolved" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm">
                        Mark Resolved
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

