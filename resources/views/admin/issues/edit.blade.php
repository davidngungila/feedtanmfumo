@extends('layouts.admin')

@section('page-title', 'Edit Issue')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Issue</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Update issue details and status</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.issues.show', $issue) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    View Details
                </a>
            </div>
        </div>
    </div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.issues.update', $issue) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member</label>
                    <input type="text" value="{{ $issue->user->name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Unassigned</option>
                        @foreach($staff as $staffMember)
                            <option value="{{ $staffMember->id }}" {{ $issue->assigned_to == $staffMember->id ? 'selected' : '' }}>{{ $staffMember->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="inquiry" {{ $issue->category == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                        <option value="complaint" {{ $issue->category == 'complaint' ? 'selected' : '' }}>Complaint</option>
                        <option value="suggestion" {{ $issue->category == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                        <option value="request" {{ $issue->category == 'request' ? 'selected' : '' }}>Request</option>
                        <option value="other" {{ $issue->category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $issue->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" {{ $issue->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ $issue->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $issue->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('description', $issue->description) }}</textarea>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                <textarea name="resolution" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('resolution', $issue->resolution) }}</textarea>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Update Issue
                </button>
                <a href="{{ route('admin.issues.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

