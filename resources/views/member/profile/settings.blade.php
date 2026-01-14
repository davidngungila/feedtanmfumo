@extends('layouts.member')

@section('page-title', 'Account Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-6">Account Settings</h2>
        
        <form action="{{ route('member.profile.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Notification Preferences -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Notification Preferences</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm sm:text-base font-medium text-gray-900">Email Notifications</p>
                                <p class="text-xs sm:text-sm text-gray-600">Receive notifications via email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ isset($preferences['email_notifications']) && $preferences['email_notifications'] ? 'checked' : 'checked' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm sm:text-base font-medium text-gray-900">SMS Notifications</p>
                                <p class="text-xs sm:text-sm text-gray-600">Receive notifications via SMS</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" {{ isset($preferences['sms_notifications']) && $preferences['sms_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Language & Region -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Language & Region</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Language</label>
                            <select name="language" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="en" {{ (isset($preferences['language']) ? $preferences['language'] : 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="sw" {{ (isset($preferences['language']) ? $preferences['language'] : '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="Africa/Dar_es_Salaam" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar es Salaam (EAT)</option>
                                <option value="UTC" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-blue-900">Account Details</p>
                                <p class="text-xs sm:text-sm text-blue-700 mt-1">To change your email or personal information, please visit the <a href="{{ route('member.profile.edit') }}" class="underline font-medium">Edit Profile</a> page.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-sm sm:text-base">
                    Save Settings
                </button>
                <a href="{{ route('member.profile.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center text-sm sm:text-base">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

