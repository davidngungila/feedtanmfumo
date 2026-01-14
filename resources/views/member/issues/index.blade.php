@extends('layouts.member')

@section('page-title', 'My Issues')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">My Issues</h1>
        <a href="{{ route('member.issues.create') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm sm:text-base">
            Report Issue
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Issues</p>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">In Progress</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Resolved</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $issue->issue_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ \Illuminate\Support\Str::limit($issue->title, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst($issue->category) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $issue->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                    ($issue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                    ($issue->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $issue->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                    ($issue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $issue->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('member.issues.show', $issue) }}" class="text-[#015425] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <p>No issues found. <a href="{{ route('member.issues.create') }}" class="text-[#015425] hover:underline">Report an issue</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($issues->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

