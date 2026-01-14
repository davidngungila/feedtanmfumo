@extends('layouts.member')

@section('page-title', 'Issue Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-[#015425]">Issue Details</h1>
        <a href="{{ route('member.issues.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-[#015425]">{{ $issue->title }}</h2>
            <span class="px-3 py-1 text-sm rounded-full {{ 
                $issue->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                ($issue->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')
            }}">
                {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
            </span>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600">Issue Number</p>
                <p class="text-lg font-semibold">{{ $issue->issue_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Category</p>
                <p class="text-lg font-semibold">{{ ucfirst($issue->category) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Priority</p>
                <span class="px-2 py-1 text-xs rounded-full {{ 
                    $issue->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                    ($issue->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                    ($issue->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                }}">
                    {{ ucfirst($issue->priority) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Created</p>
                <p class="text-lg font-semibold">{{ $issue->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-4">
            <p class="text-sm text-gray-600 mb-2">Description</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $issue->description }}</p>
        </div>
        
        @if($issue->resolution)
        <div class="border-t border-gray-200 pt-4 mt-4">
            <p class="text-sm text-gray-600 mb-2">Resolution</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $issue->resolution }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

