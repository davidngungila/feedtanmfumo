@extends('layouts.admin')

@section('page-title', 'Issues Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Issues Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Track and resolve system issues and support requests</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.issues.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Issue
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Total Issues</p>
            <p class="text-2xl font-bold text-[#015425]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">In Progress</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Resolved</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">High Priority</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['high_priority'] }}</p>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.issues.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search issues..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Categories</option>
                    <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                    <option value="suggestion" {{ request('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                    <option value="inquiry" {{ request('category') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                    <option value="request" {{ request('category') == 'request' ? 'selected' : '' }}>Request</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Filter
                </button>
                <a href="{{ route('admin.issues.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-[#015425]">All Issues</h2>
        <a href="{{ route('admin.issues.create') }}" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
            + New Issue
        </a>
    </div>

    <!-- Bulk Actions (Hidden by default, shown when items selected) -->
    <div id="bulkActionsBar" class="hidden bg-[#f0fdf4] border border-[#dcfce7] rounded-lg p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center">
            <span class="text-sm font-medium text-gray-700 mr-4"><span id="selectedCount">0</span> issues selected</span>
            <button type="button" id="clearSelection" class="text-sm text-red-600 hover:underline">Clear Selection</button>
        </div>
        
        <form id="bulkUpdateForm" method="POST" action="{{ route('admin.issues.bulk-update') }}" class="flex flex-wrap items-center gap-3">
            @csrf
            <div id="selectedIdsContainer"></div>
            
            <select name="action" id="bulkActionType" class="text-sm border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <option value="">Choose Bulk Action...</option>
                <option value="status">Update Status</option>
                <option value="priority">Update Priority</option>
                <option value="assign">Assign To</option>
            </select>

            <!-- Action Sub-options (Dynamic) -->
            <div id="bulkStatusWrapper" class="hidden">
                <select name="status" class="text-sm border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div id="bulkPriorityWrapper" class="hidden">
                <select name="priority" class="text-sm border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div id="bulkAssignWrapper" class="hidden">
                <select name="assigned_to" class="text-sm border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">Select Staff...</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-[#015425] text-white text-sm font-bold rounded hover:bg-[#013019] transition">
                Apply to Selected
            </button>
        </form>
    </div>

    <!-- Issues Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="issue_ids[]" value="{{ $issue->id }}" class="issue-checkbox rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            </td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $issue->issue_number }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($issue->title, 40) }}</td>
                            <td class="px-4 py-3 text-sm">{{ $issue->user->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($issue->category) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $issue->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                    ($issue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                    ($issue->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $issue->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                    ($issue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                    ($issue->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $issue->assignedTo->name ?? 'Unassigned' }}</td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <a href="{{ route('admin.issues.show', $issue) }}" class="text-[#015425] hover:underline">View</a>
                                <a href="{{ route('admin.issues.edit', $issue) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">No issues found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $issues->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.issue-checkbox');
    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const clearSelection = document.getElementById('clearSelection');
    const bulkActionType = document.getElementById('bulkActionType');
    const bulkStatusWrapper = document.getElementById('bulkStatusWrapper');
    const bulkPriorityWrapper = document.getElementById('bulkPriorityWrapper');
    const bulkAssignWrapper = document.getElementById('bulkAssignWrapper');
    const bulkUpdateForm = document.getElementById('bulkUpdateForm');
    const selectedIdsContainer = document.getElementById('selectedIdsContainer');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.issue-checkbox:checked');
        const count = checked.length;
        
        selectedCountSpan.textContent = count;
        
        if (count > 0) {
            bulkBar.classList.remove('hidden');
        } else {
            bulkBar.classList.add('hidden');
        }
        
        // Update hidden inputs for form
        selectedIdsContainer.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'issue_ids[]';
            input.value = cb.value;
            selectedIdsContainer.appendChild(input);
        });
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkBar();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) selectAll.checked = false;
            if (document.querySelectorAll('.issue-checkbox:checked').length === checkboxes.length) selectAll.checked = true;
            updateBulkBar();
        });
    });

    clearSelection.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        selectAll.checked = false;
        updateBulkBar();
    });

    bulkActionType.addEventListener('change', function() {
        const type = this.value;
        bulkStatusWrapper.classList.add('hidden');
        bulkPriorityWrapper.classList.add('hidden');
        bulkAssignWrapper.classList.add('hidden');

        if (type === 'status') bulkStatusWrapper.classList.remove('hidden');
        if (type === 'priority') bulkPriorityWrapper.classList.remove('hidden');
        if (type === 'assign') bulkAssignWrapper.classList.remove('hidden');
    });

    bulkUpdateForm.addEventListener('submit', function(e) {
        if (!bulkActionType.value) {
            e.preventDefault();
            alert('Please select an action type');
            return;
        }
        
        const count = document.querySelectorAll('.issue-checkbox:checked').length;
        if (!confirm(`Are you sure you want to update ${count} issues?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection

