@extends('layouts.admin')

@section('page-title', 'System Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure application settings, timezone, currency, and system preferences</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.settings.system.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Application Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                            <input type="text" name="app_name" value="{{ isset($settings['app_name']) ? $settings['app_name']->value : 'FEEDTAN DIGITAL' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application URL</label>
                            <input type="url" name="app_url" value="{{ $settings['app_url']->value ?? url('/') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="Africa/Dar_es_Salaam" {{ (isset($settings['timezone']) ? $settings['timezone']->value : 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar es Salaam</option>
                                <option value="UTC">UTC</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                            <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'TZS' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                            <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol']->value ?? 'TZS' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                            <select name="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="Y-m-d" {{ (isset($settings['date_format']) ? $settings['date_format']->value : 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                <option value="d/m/Y" {{ ($settings['date_format']->value ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ ($settings['date_format']->value ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">System Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="maintenance_mode" value="1" id="maintenance_mode" {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            <label for="maintenance_mode" class="text-sm font-medium text-gray-700">Enable Maintenance Mode</label>
                        </div>
                        <p class="text-xs text-gray-500">When enabled, only administrators can access the system</p>
                        
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-900">System Information</p>
                                    <p class="text-xs text-blue-700 mt-1">PHP Version: {{ phpversion() }} | Laravel Version: {{ app()->version() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Regional Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                            <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="en" {{ (isset($settings['language']) ? $settings['language']->value : 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="sw" {{ (isset($settings['language']) ? $settings['language']->value : '') == 'sw' ? 'selected' : '' }}>Kiswahili</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number Format</label>
                            <select name="number_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="1,234.56" {{ (isset($settings['number_format']) ? $settings['number_format']->value : '1,234.56') == '1,234.56' ? 'selected' : '' }}>1,234.56 (US Format)</option>
                                <option value="1.234,56" {{ (isset($settings['number_format']) ? $settings['number_format']->value : '') == '1.234,56' ? 'selected' : '' }}>1.234,56 (European Format)</option>
                                <option value="1 234.56" {{ (isset($settings['number_format']) ? $settings['number_format']->value : '') == '1 234.56' ? 'selected' : '' }}>1 234.56 (Space Separated)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cache Duration (minutes)</label>
                            <input type="number" name="cache_duration" value="{{ isset($settings['cache_duration']) ? $settings['cache_duration']->value : '60' }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <p class="text-xs text-gray-500 mt-1">How long to cache data before refreshing</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                            <input type="number" name="session_timeout" value="{{ isset($settings['session_timeout']) ? $settings['session_timeout']->value : '120' }}" min="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <p class="text-xs text-gray-500 mt-1">User session timeout duration</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

