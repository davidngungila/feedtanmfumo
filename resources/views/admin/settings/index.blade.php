@extends('layouts.admin')

@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage all system configurations and preferences</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Settings</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['total_settings']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">System Settings</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($stats['system_settings']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Organization Settings</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['organization_settings']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Communication Settings</p>
            <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ number_format($stats['communication_settings']) }}</p>
        </div>
    </div>

    <!-- Settings Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- System Settings -->
        <a href="{{ route('admin.settings.system') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">System Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure application settings, timezone, currency, and maintenance mode</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Organization Settings -->
        <a href="{{ route('admin.settings.organization') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Organization Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Manage organization information, contact details, and registration data</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Product Configuration -->
        <a href="{{ route('admin.settings.product-configuration') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Product Configuration</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure loan products, savings accounts, and investment plans</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Security Settings -->
        <a href="{{ route('admin.settings.security') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Security Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Manage password policies, session settings, and security features</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Communication Settings -->
        <a href="{{ route('admin.settings.communication') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Communication Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure email and SMS communication settings</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Email Settings -->
        <a href="{{ route('admin.settings.email-settings') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Email Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure SMTP settings and email templates</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- SMS Templates -->
        <a href="{{ route('admin.settings.sms-templates') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-200 transition">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">SMS Templates</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Manage SMS message templates for notifications</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Notification Preferences -->
        <a href="{{ route('admin.settings.notification-preferences') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Notification Preferences</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure notification channels and preferences</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <!-- Reminder Settings -->
        <a href="{{ route('admin.settings.reminder-settings') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 ml-4">Reminder Settings</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure automated reminders for loans and payments</p>
            <div class="flex items-center text-[#015425] font-medium group-hover:underline">
                <span>Configure</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Advanced Settings Sections -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Advanced Settings</h2>
        
        <!-- User & Access Management -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                User & Access Management
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.users') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Users</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage system users</p>
                </a>
                <a href="{{ route('admin.system-settings.roles') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Roles</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage user roles</p>
                </a>
                <a href="{{ route('admin.system-settings.permissions') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Permissions</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage system permissions</p>
                </a>
                <a href="{{ route('admin.system-settings.role-assignment') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Role Assignment</h4>
                    <p class="text-sm text-gray-600 mt-1">Assign roles to users</p>
                </a>
                <a href="{{ route('admin.system-settings.login-sessions') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">Login Sessions</h4>
                    <p class="text-sm text-gray-600 mt-1">View active login sessions</p>
                </a>
                <a href="{{ route('admin.system-settings.password-policy') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-red-500">
                    <h4 class="font-semibold text-gray-900">Password Policy</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure password requirements</p>
                </a>
            </div>
        </div>

        <!-- Organization / General -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Organization / General
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.system-information') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">System Information</h4>
                    <p class="text-sm text-gray-600 mt-1">View system details</p>
                </a>
                <a href="{{ route('admin.system-settings.organization-profile') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Organization Profile</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage organization details</p>
                </a>
                <a href="{{ route('admin.system-settings.contact-details') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Contact Details</h4>
                    <p class="text-sm text-gray-600 mt-1">Update contact information</p>
                </a>
                <a href="{{ route('admin.system-settings.logo-branding') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Logo & Branding</h4>
                    <p class="text-sm text-gray-600 mt-1">Customize branding</p>
                </a>
                <a href="{{ route('admin.system-settings.language-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">Language Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure languages</p>
                </a>
                <a href="{{ route('admin.system-settings.timezone-date-format') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-red-500">
                    <h4 class="font-semibold text-gray-900">Timezone & Date Format</h4>
                    <p class="text-sm text-gray-600 mt-1">Set timezone and date format</p>
                </a>
            </div>
        </div>

        <!-- Application Settings -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Application Settings
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.general-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">General Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Basic application settings</p>
                </a>
                <a href="{{ route('admin.system-settings.feature-toggles') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Feature Toggles</h4>
                    <p class="text-sm text-gray-600 mt-1">Enable/disable features</p>
                </a>
                <a href="{{ route('admin.system-settings.maintenance-mode') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-red-500">
                    <h4 class="font-semibold text-gray-900">Maintenance Mode</h4>
                    <p class="text-sm text-gray-600 mt-1">Control maintenance mode</p>
                </a>
                <a href="{{ route('admin.system-settings.default-values') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Default Values</h4>
                    <p class="text-sm text-gray-600 mt-1">Set default system values</p>
                </a>
                <a href="{{ route('admin.system-settings.system-preferences') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">System Preferences</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure preferences</p>
                </a>
            </div>
        </div>

        <!-- Notifications -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notifications
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.email-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Email Settings (SMTP)</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure email server</p>
                </a>
                <a href="{{ route('admin.system-settings.sms-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">SMS Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure SMS provider</p>
                </a>
                <a href="{{ route('admin.system-settings.push-notifications') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Push Notifications</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure push notifications</p>
                </a>
                <a href="{{ route('admin.system-settings.notification-templates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Notification Templates</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage notification templates</p>
                </a>
                <a href="{{ route('admin.system-settings.alert-rules') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-red-500">
                    <h4 class="font-semibold text-gray-900">Alert Rules</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure alert rules</p>
                </a>
            </div>
        </div>

        <!-- Data Management -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                </svg>
                Data Management
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.backup-restore') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Backup & Restore</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage system backups</p>
                </a>
                <a href="{{ route('admin.system-settings.import-data') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Import Data</h4>
                    <p class="text-sm text-gray-600 mt-1">Import data from files</p>
                </a>
                <a href="{{ route('admin.system-settings.export-data') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Export Data</h4>
                    <p class="text-sm text-gray-600 mt-1">Export system data</p>
                </a>
                <a href="{{ route('admin.system-settings.database-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Database Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Database configuration</p>
                </a>
                <a href="{{ route('admin.system-settings.data-retention-policy') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">Data Retention Policy</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure data retention</p>
                </a>
            </div>
        </div>

        <!-- Security -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Security
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.security-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Security Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">General security configuration</p>
                </a>
                <a href="{{ route('admin.system-settings.two-factor-auth') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Two-Factor Authentication</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure 2FA</p>
                </a>
                <a href="{{ route('admin.system-settings.ip-whitelisting') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">IP Whitelisting</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage IP access control</p>
                </a>
                <a href="{{ route('admin.system-settings.audit-logs') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Audit Logs</h4>
                    <p class="text-sm text-gray-600 mt-1">View system audit logs</p>
                </a>
                <a href="{{ route('admin.system-settings.activity-logs') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">Activity Logs</h4>
                    <p class="text-sm text-gray-600 mt-1">View user activity logs</p>
                </a>
            </div>
        </div>

        <!-- Documents & Templates -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Documents & Templates
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.pdf-templates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">PDF Templates</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage PDF templates</p>
                </a>
                <a href="{{ route('admin.system-settings.email-templates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Email Templates</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage email templates</p>
                </a>
                <a href="{{ route('admin.system-settings.report-templates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Report Templates</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage report templates</p>
                </a>
                <a href="{{ route('admin.system-settings.certificate-templates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Certificate Templates</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage certificate templates</p>
                </a>
            </div>
        </div>

        <!-- Integrations -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Integrations
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.api-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">API Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure API access</p>
                </a>
                <a href="{{ route('admin.system-settings.payment-gateways') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Payment Gateways</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage payment integrations</p>
                </a>
                <a href="{{ route('admin.system-settings.third-party-services') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Third-party Services</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage third-party integrations</p>
                </a>
                <a href="{{ route('admin.system-settings.webhooks') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Webhooks</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure webhooks</p>
                </a>
            </div>
        </div>

        <!-- System Tools -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                System Tools
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.cache-management') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Cache Management</h4>
                    <p class="text-sm text-gray-600 mt-1">Clear and manage cache</p>
                </a>
                <a href="{{ route('admin.system-settings.system-logs') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">System Logs</h4>
                    <p class="text-sm text-gray-600 mt-1">View system logs</p>
                </a>
                <a href="{{ route('admin.system-settings.queue-jobs') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Queue Jobs</h4>
                    <p class="text-sm text-gray-600 mt-1">Monitor queue jobs</p>
                </a>
                <a href="{{ route('admin.system-settings.cron-jobs') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Cron Jobs</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage scheduled tasks</p>
                </a>
                <a href="{{ route('admin.system-settings.debug-settings') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">Debug Settings</h4>
                    <p class="text-sm text-gray-600 mt-1">Configure debug options</p>
                </a>
            </div>
        </div>

        <!-- Updates & Maintenance -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Updates & Maintenance
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.system-updates') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">System Updates</h4>
                    <p class="text-sm text-gray-600 mt-1">Check for updates</p>
                </a>
                <a href="{{ route('admin.system-settings.version-info') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Version Info</h4>
                    <p class="text-sm text-gray-600 mt-1">Current version details</p>
                </a>
                <a href="{{ route('admin.system-settings.changelog') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Changelog</h4>
                    <p class="text-sm text-gray-600 mt-1">View version history</p>
                </a>
                <a href="{{ route('admin.system-settings.optimize-system') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Optimize System</h4>
                    <p class="text-sm text-gray-600 mt-1">Optimize system performance</p>
                </a>
            </div>
        </div>

        <!-- Reports & Monitoring -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Reports & Monitoring
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.system-reports') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">System Reports</h4>
                    <p class="text-sm text-gray-600 mt-1">View system reports</p>
                </a>
                <a href="{{ route('admin.system-settings.usage-statistics') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Usage Statistics</h4>
                    <p class="text-sm text-gray-600 mt-1">View usage statistics</p>
                </a>
                <a href="{{ route('admin.system-settings.performance-monitoring') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Performance Monitoring</h4>
                    <p class="text-sm text-gray-600 mt-1">Monitor system performance</p>
                </a>
                <a href="{{ route('admin.system-settings.error-reports') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-red-500">
                    <h4 class="font-semibold text-gray-900">Error Reports</h4>
                    <p class="text-sm text-gray-600 mt-1">View error reports</p>
                </a>
            </div>
        </div>

        <!-- Bonus Features -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                Bonus Features
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.system-settings.custom-fields') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-blue-500">
                    <h4 class="font-semibold text-gray-900">Custom Fields</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage custom fields</p>
                </a>
                <a href="{{ route('admin.system-settings.module-management') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-green-500">
                    <h4 class="font-semibold text-gray-900">Module Management</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage system modules</p>
                </a>
                <a href="{{ route('admin.system-settings.menu-builder') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-purple-500">
                    <h4 class="font-semibold text-gray-900">Menu Builder</h4>
                    <p class="text-sm text-gray-600 mt-1">Customize menu structure</p>
                </a>
                <a href="{{ route('admin.system-settings.theme-appearance') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-indigo-500">
                    <h4 class="font-semibold text-gray-900">Theme / Appearance</h4>
                    <p class="text-sm text-gray-600 mt-1">Customize theme</p>
                </a>
                <a href="{{ route('admin.system-settings.license-management') }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition border-l-4 border-yellow-500">
                    <h4 class="font-semibold text-gray-900">License Management</h4>
                    <p class="text-sm text-gray-600 mt-1">Manage system license</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

