@extends('layouts.admin')

@section('page-title', 'System Logs')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Logs</h1>
        <p class="text-white text-opacity-90">View system log files</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm text-green-400 max-h-96 overflow-y-auto">
            @if(count($logs) > 0)
                @foreach(array_reverse($logs) as $log)
                <div class="mb-1">{{ $log }}</div>
                @endforeach
            @else
                <div class="text-gray-500">No log entries found</div>
            @endif
        </div>
    </div>
</div>
@endsection


