@extends('layouts.admin')

@section('page-title', 'Usage Statistics')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Usage Statistics</h1>
        <p class="text-white text-opacity-90">View system usage statistics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Total Users</div>
            <div class="text-3xl font-bold text-[#015425]">{{ number_format($stats['total_users']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Active Users</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['active_users']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Logins Today</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_logins_today']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-600 mb-1">Audit Logs</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_audit_logs']) }}</div>
        </div>
    </div>
</div>
@endsection

