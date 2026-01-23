@extends('layouts.admin')

@section('page-title', 'Performance Monitoring')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Performance Monitoring</h1>
        <p class="text-white text-opacity-90">Monitor system performance metrics</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-gray-600 mb-1">Response Time</div>
                    <div class="text-2xl font-bold text-green-600">120ms</div>
                    <div class="text-xs text-gray-500 mt-1">Average</div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-gray-600 mb-1">Database Queries</div>
                    <div class="text-2xl font-bold text-blue-600">45</div>
                    <div class="text-xs text-gray-500 mt-1">Per request</div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-gray-600 mb-1">Memory Usage</div>
                    <div class="text-2xl font-bold text-purple-600">256MB</div>
                    <div class="text-xs text-gray-500 mt-1">Current</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

