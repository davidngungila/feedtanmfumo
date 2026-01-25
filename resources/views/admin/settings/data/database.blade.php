@extends('layouts.admin')

@section('page-title', 'Database Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Database Settings</h1>
        <p class="text-white text-opacity-90">View database configuration</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Connection</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['connection'] }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Host</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['host'] }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Database</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['database'] }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Charset</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['charset'] }}</div>
                </div>
            </div>
            <div class="pt-4 border-t">
                <p class="text-sm text-gray-500">Database settings are configured in the environment file. Please contact your system administrator to make changes.</p>
            </div>
        </div>
    </div>
</div>
@endsection


