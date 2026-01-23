@extends('layouts.admin')

@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Security Settings</h1>
        <p class="text-white text-opacity-90">Configure general security settings</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.security-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                        <input type="number" name="max_login_attempts" value="{{ $settings['max_login_attempts']->value ?? '5' }}" 
                               min="1" max="10"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lockout Duration (minutes)</label>
                        <input type="number" name="lockout_duration_minutes" value="{{ $settings['lockout_duration_minutes']->value ?? '30' }}" 
                               min="1"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout_minutes" value="{{ $settings['session_timeout_minutes']->value ?? '30' }}" 
                           min="5"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="two_factor_enabled" value="1" 
                           {{ ($settings['two_factor_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <label class="ml-2 text-sm text-gray-700">Enable Two-Factor Authentication</label>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

