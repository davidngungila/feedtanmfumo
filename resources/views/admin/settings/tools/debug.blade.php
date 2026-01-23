@extends('layouts.admin')

@section('page-title', 'Debug Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Debug Settings</h1>
        <p class="text-white text-opacity-90">Configure debug and development settings</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-4">
            <div class="p-4 border-2 rounded-lg {{ $settings['debug'] ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Debug Mode</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($settings['debug'])
                                Debug mode is enabled. This should be disabled in production.
                            @else
                                Debug mode is disabled. System is running in production mode.
                            @endif
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $settings['debug'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $settings['debug'] ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Environment</h3>
                <p class="text-sm text-gray-600">{{ ucfirst($settings['environment']) }}</p>
            </div>
            <div class="pt-4 border-t">
                <p class="text-sm text-gray-500">Debug settings are configured in the environment file. Please contact your system administrator to make changes.</p>
            </div>
        </div>
    </div>
</div>
@endsection

