@extends('layouts.admin')

@section('page-title', 'System Reports')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Reports</h1>
                <p class="text-white text-opacity-90">Generate and view system reports</p>
            </div>
            <a href="{{ route('admin.settings.system-reports.pdf') }}" target="_blank" class="bg-white text-[#015425] px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
        </div>
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


