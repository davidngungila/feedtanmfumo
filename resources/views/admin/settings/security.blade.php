@extends('layouts.admin')

@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Security Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage password policies, session settings, and security features</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.security.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Password Policy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Password Policy
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                    <input type="number" name="password_min_length" value="{{ isset($settings['password_min_length']) ? $settings['password_min_length']->value : '8' }}" min="6" max="32" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Minimum number of characters required</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Expiry (Days)</label>
                    <input type="number" name="password_expiry_days" value="{{ isset($settings['password_expiry_days']) ? $settings['password_expiry_days']->value : '90' }}" min="0" max="365" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Number of days before password expires (0 = never)</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Password Requirements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="password_require_uppercase" value="1" id="password_require_uppercase" {{ (isset($settings['password_require_uppercase']) && $settings['password_require_uppercase']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label for="password_require_uppercase" class="text-sm font-medium text-gray-700">Require Uppercase Letters</label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="password_require_lowercase" value="1" id="password_require_lowercase" {{ (isset($settings['password_require_lowercase']) && $settings['password_require_lowercase']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label for="password_require_lowercase" class="text-sm font-medium text-gray-700">Require Lowercase Letters</label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="password_require_numbers" value="1" id="password_require_numbers" {{ (isset($settings['password_require_numbers']) && $settings['password_require_numbers']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label for="password_require_numbers" class="text-sm font-medium text-gray-700">Require Numbers</label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="password_require_symbols" value="1" id="password_require_symbols" {{ (isset($settings['password_require_symbols']) && $settings['password_require_symbols']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label for="password_require_symbols" class="text-sm font-medium text-gray-700">Require Special Characters</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Security -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Login Security
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Login Attempts</label>
                    <input type="number" name="max_login_attempts" value="{{ isset($settings['max_login_attempts']) ? $settings['max_login_attempts']->value : '5' }}" min="1" max="10" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Number of failed attempts before account lockout</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lockout Duration (Minutes)</label>
                    <input type="number" name="lockout_duration_minutes" value="{{ isset($settings['lockout_duration_minutes']) ? $settings['lockout_duration_minutes']->value : '30' }}" min="1" max="1440" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Time to wait before allowing login attempts again</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (Minutes)</label>
                    <input type="number" name="session_timeout_minutes" value="{{ isset($settings['session_timeout_minutes']) ? $settings['session_timeout_minutes']->value : '120' }}" min="5" max="480" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Inactive session timeout duration</p>
                </div>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Two-Factor Authentication
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="two_factor_enabled" value="1" id="two_factor_enabled" {{ (isset($settings['two_factor_enabled']) && $settings['two_factor_enabled']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <label for="two_factor_enabled" class="text-sm font-medium text-gray-700">Enable Two-Factor Authentication</label>
                </div>
                <p class="text-xs text-gray-500 ml-8">Require users to verify their identity using a second factor (e.g., SMS code)</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save Security Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

