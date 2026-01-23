@extends('layouts.admin')

@section('page-title', 'System Information')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Information</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View system configuration and environment details</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <!-- System Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PHP Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">PHP Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">PHP Version</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $info['php_version'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Laravel Version</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $info['laravel_version'] }}</dd>
                </div>
            </dl>
        </div>

        <!-- Server Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Server Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Server Software</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $info['server'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Database</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($info['database']) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Application Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Configuration</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Environment</dt>
                    <dd class="text-sm font-medium">
                        <span class="px-2 py-1 rounded {{ $info['environment'] === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($info['environment']) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Debug Mode</dt>
                    <dd class="text-sm font-medium">
                        <span class="px-2 py-1 rounded {{ $info['debug'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $info['debug'] ? 'Enabled' : 'Disabled' }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Timezone</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $info['timezone'] }}</dd>
                </div>
            </dl>
        </div>

        <!-- Cache & Queue Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cache & Queue</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Cache Driver</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($info['cache_driver']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Queue Driver</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($info['queue_driver']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-600">Session Driver</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($info['session_driver']) }}</dd>
                </div>
            </dl>
        </div>
    </div>

</div>
@endsection

