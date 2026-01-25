@extends('layouts.admin')

@section('page-title', 'System Reports')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Reports</h1>
        <p class="text-white text-opacity-90">Generate and view system reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="#" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-blue-500">
            <h3 class="font-semibold text-gray-900 mb-2">User Activity Report</h3>
            <p class="text-sm text-gray-600">View user activity and login statistics</p>
        </a>
        <a href="#" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-green-500">
            <h3 class="font-semibold text-gray-900 mb-2">System Health Report</h3>
            <p class="text-sm text-gray-600">Monitor system performance and health</p>
        </a>
        <a href="#" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-purple-500">
            <h3 class="font-semibold text-gray-900 mb-2">Security Audit Report</h3>
            <p class="text-sm text-gray-600">Review security events and audit logs</p>
        </a>
    </div>
</div>
@endsection


