@extends('layouts.admin')

@section('page-title', 'Usage Statistics')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Usage Statistics</h1>
                <p class="text-white text-opacity-90">View system usage statistics</p>
            </div>
            <a href="{{ route('system-settings.usage-statistics.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
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


