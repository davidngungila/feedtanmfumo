@extends('layouts.admin')

@section('page-title', 'Password Policy')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Password Policy</h1>
        <p class="text-white text-opacity-90">Configure password requirements and security settings</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.password-policy.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Password Length -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                    <input type="number" name="min_length" value="{{ old('min_length', $policy->min_length ?? 8) }}" 
                           min="6" max="32" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    @error('min_length')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="require_uppercase" value="1" 
                               {{ old('require_uppercase', $policy->require_uppercase ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label class="ml-2 text-sm text-gray-700">Require Uppercase Letters</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="require_lowercase" value="1" 
                               {{ old('require_lowercase', $policy->require_lowercase ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label class="ml-2 text-sm text-gray-700">Require Lowercase Letters</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="require_numbers" value="1" 
                               {{ old('require_numbers', $policy->require_numbers ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label class="ml-2 text-sm text-gray-700">Require Numbers</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="require_symbols" value="1" 
                               {{ old('require_symbols', $policy->require_symbols ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <label class="ml-2 text-sm text-gray-700">Require Special Characters</label>
                    </div>
                </div>

                <!-- Password Age -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Password Age (days)</label>
                        <input type="number" name="max_age_days" value="{{ old('max_age_days', $policy->max_age_days) }}" 
                               min="0" placeholder="0 = Never expires"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Age (days)</label>
                        <input type="number" name="min_age_days" value="{{ old('min_age_days', $policy->min_age_days ?? 0) }}" 
                               min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>

                <!-- Password History -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password History Count</label>
                    <input type="number" name="history_count" value="{{ old('history_count', $policy->history_count ?? 0) }}" 
                           min="0" max="10" placeholder="Remember last N passwords"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-sm text-gray-500">Number of previous passwords to remember (0-10)</p>
                </div>

                <!-- Account Lockout -->
                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Lockout Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Login Attempts</label>
                            <input type="number" name="lockout_attempts" value="{{ old('lockout_attempts', $policy->lockout_attempts ?? 5) }}" 
                                   min="1" max="10" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lockout Duration (minutes)</label>
                            <input type="number" name="lockout_duration_minutes" value="{{ old('lockout_duration_minutes', $policy->lockout_duration_minutes ?? 30) }}" 
                                   min="1" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        </div>
                    </div>
                </div>

                <!-- Enforcement -->
                <div class="flex items-center">
                    <input type="checkbox" name="enforce_on_login" value="1" 
                           {{ old('enforce_on_login', $policy->enforce_on_login ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <label class="ml-2 text-sm text-gray-700">Enforce Policy on Login</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.settings.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

