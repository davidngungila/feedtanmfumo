@extends('layouts.admin')

@section('page-title', 'Account Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Account Settings</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Customize your account preferences, notifications, security, and display options</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Notification Preferences -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Notification Preferences</h2>
                
                <!-- Notification Channels -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Notification Channels</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Email Notifications</p>
                                    <p class="text-sm text-gray-600">Receive notifications via email</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ isset($preferences['email_notifications']) && $preferences['email_notifications'] ? 'checked' : 'checked' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">SMS Notifications</p>
                                    <p class="text-sm text-gray-600">Receive notifications via SMS</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer" {{ isset($preferences['sms_notifications']) && $preferences['sms_notifications'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">In-App Notifications</p>
                                    <p class="text-sm text-gray-600">Show notifications within the application</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="in_app_notifications" value="1" class="sr-only peer" {{ isset($preferences['in_app_notifications']) && $preferences['in_app_notifications'] ? 'checked' : 'checked' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notification Types -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Notification Types</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Loan Applications</p>
                                <p class="text-xs text-gray-600">New loan applications</p>
                            </div>
                            <input type="checkbox" name="notify_loan_applications" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_loan_applications']) && $preferences['notify_loan_applications'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Payment Reminders</p>
                                <p class="text-xs text-gray-600">Upcoming payment due dates</p>
                            </div>
                            <input type="checkbox" name="notify_payment_reminders" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_payment_reminders']) && $preferences['notify_payment_reminders'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Overdue Payments</p>
                                <p class="text-xs text-gray-600">Overdue loan payments</p>
                            </div>
                            <input type="checkbox" name="notify_overdue_payments" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_overdue_payments']) && $preferences['notify_overdue_payments'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">New Members</p>
                                <p class="text-xs text-gray-600">New member registrations</p>
                            </div>
                            <input type="checkbox" name="notify_new_members" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_new_members']) && $preferences['notify_new_members'] ? 'checked' : '' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">System Alerts</p>
                                <p class="text-xs text-gray-600">System maintenance and updates</p>
                            </div>
                            <input type="checkbox" name="notify_system_alerts" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_system_alerts']) && $preferences['notify_system_alerts'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Issue Reports</p>
                                <p class="text-xs text-gray-600">New issue submissions</p>
                            </div>
                            <input type="checkbox" name="notify_issue_reports" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_issue_reports']) && $preferences['notify_issue_reports'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Account Activity</p>
                                <p class="text-xs text-gray-600">Login and security events</p>
                            </div>
                            <input type="checkbox" name="notify_account_activity" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_account_activity']) && $preferences['notify_account_activity'] ? 'checked' : 'checked' }}>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Reports Ready</p>
                                <p class="text-xs text-gray-600">Generated reports available</p>
                            </div>
                            <input type="checkbox" name="notify_reports_ready" value="1" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ isset($preferences['notify_reports_ready']) && $preferences['notify_reports_ready'] ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <!-- Email Digest -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Email Digest</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Digest Frequency</label>
                            <select name="email_digest_frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="realtime" {{ (isset($preferences['email_digest_frequency']) ? $preferences['email_digest_frequency'] : 'realtime') == 'realtime' ? 'selected' : '' }}>Real-time</option>
                                <option value="daily" {{ (isset($preferences['email_digest_frequency']) ? $preferences['email_digest_frequency'] : '') == 'daily' ? 'selected' : '' }}>Daily Summary</option>
                                <option value="weekly" {{ (isset($preferences['email_digest_frequency']) ? $preferences['email_digest_frequency'] : '') == 'weekly' ? 'selected' : '' }}>Weekly Summary</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Digest Time</label>
                            <input type="time" name="email_digest_time" value="{{ isset($preferences['email_digest_time']) ? $preferences['email_digest_time'] : '09:00' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Preferences -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Display Preferences</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                        <select name="theme" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="light" {{ (isset($preferences['theme']) ? $preferences['theme'] : 'light') == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ (isset($preferences['theme']) ? $preferences['theme'] : '') == 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="auto" {{ (isset($preferences['theme']) ? $preferences['theme'] : '') == 'auto' ? 'selected' : '' }}>Auto (System)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select name="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="Y-m-d" {{ (isset($preferences['date_format']) ? $preferences['date_format'] : 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="d/m/Y" {{ (isset($preferences['date_format']) ? $preferences['date_format'] : '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="m/d/Y" {{ (isset($preferences['date_format']) ? $preferences['date_format'] : '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                            <option value="d M Y" {{ (isset($preferences['date_format']) ? $preferences['date_format'] : '') == 'd M Y' ? 'selected' : '' }}>DD MMM YYYY</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                        <select name="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="24" {{ (isset($preferences['time_format']) ? $preferences['time_format'] : '24') == '24' ? 'selected' : '' }}>24-hour (HH:MM)</option>
                            <option value="12" {{ (isset($preferences['time_format']) ? $preferences['time_format'] : '') == '12' ? 'selected' : '' }}>12-hour (AM/PM)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number Format</label>
                        <select name="number_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="comma" {{ (isset($preferences['number_format']) ? $preferences['number_format'] : 'comma') == 'comma' ? 'selected' : '' }}>1,234.56 (Comma)</option>
                            <option value="space" {{ (isset($preferences['number_format']) ? $preferences['number_format'] : '') == 'space' ? 'selected' : '' }}>1 234.56 (Space)</option>
                            <option value="period" {{ (isset($preferences['number_format']) ? $preferences['number_format'] : '') == 'period' ? 'selected' : '' }}>1.234,56 (Period)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Items Per Page</label>
                        <select name="items_per_page" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="10" {{ (isset($preferences['items_per_page']) ? $preferences['items_per_page'] : '20') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ (isset($preferences['items_per_page']) ? $preferences['items_per_page'] : '20') == '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ (isset($preferences['items_per_page']) ? $preferences['items_per_page'] : '') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ (isset($preferences['items_per_page']) ? $preferences['items_per_page'] : '') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                        <select name="currency_symbol" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="TZS" {{ (isset($preferences['currency_symbol']) ? $preferences['currency_symbol'] : 'TZS') == 'TZS' ? 'selected' : '' }}>TZS (Tanzanian Shilling)</option>
                            <option value="USD" {{ (isset($preferences['currency_symbol']) ? $preferences['currency_symbol'] : '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ (isset($preferences['currency_symbol']) ? $preferences['currency_symbol'] : '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Language & Region -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Language & Region</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                        <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="en" {{ (isset($preferences['language']) ? $preferences['language'] : 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="sw" {{ (isset($preferences['language']) ? $preferences['language'] : '') == 'sw' ? 'selected' : '' }}>Swahili</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="Africa/Dar_es_Salaam" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar es Salaam (EAT)</option>
                            <option value="Africa/Nairobi" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : '') == 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (EAT)</option>
                            <option value="Africa/Kampala" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : '') == 'Africa/Kampala' ? 'selected' : '' }}>Africa/Kampala (EAT)</option>
                            <option value="UTC" {{ (isset($preferences['timezone']) ? $preferences['timezone'] : '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Security Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Two-Factor Authentication</p>
                            <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="two_factor_enabled" value="1" class="sr-only peer" {{ isset($preferences['two_factor_enabled']) && $preferences['two_factor_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                            <select name="session_timeout" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="15" {{ (isset($preferences['session_timeout']) ? $preferences['session_timeout'] : '30') == '15' ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ (isset($preferences['session_timeout']) ? $preferences['session_timeout'] : '30') == '30' ? 'selected' : '' }}>30 minutes</option>
                                <option value="60" {{ (isset($preferences['session_timeout']) ? $preferences['session_timeout'] : '') == '60' ? 'selected' : '' }}>1 hour</option>
                                <option value="120" {{ (isset($preferences['session_timeout']) ? $preferences['session_timeout'] : '') == '120' ? 'selected' : '' }}>2 hours</option>
                                <option value="0" {{ (isset($preferences['session_timeout']) ? $preferences['session_timeout'] : '') == '0' ? 'selected' : '' }}>Never</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Login Alerts</label>
                            <select name="login_alerts" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="all" {{ (isset($preferences['login_alerts']) ? $preferences['login_alerts'] : 'all') == 'all' ? 'selected' : '' }}>All Logins</option>
                                <option value="new_device" {{ (isset($preferences['login_alerts']) ? $preferences['login_alerts'] : '') == 'new_device' ? 'selected' : '' }}>New Devices Only</option>
                                <option value="none" {{ (isset($preferences['login_alerts']) ? $preferences['login_alerts'] : '') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Activity Logging</p>
                            <p class="text-sm text-gray-600">Track and log all account activities</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="activity_logging" value="1" class="sr-only peer" {{ isset($preferences['activity_logging']) && $preferences['activity_logging'] ? 'checked' : 'checked' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Dashboard Preferences -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Dashboard Preferences</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Dashboard View</label>
                        <select name="default_dashboard" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <option value="overview" {{ (isset($preferences['default_dashboard']) ? $preferences['default_dashboard'] : 'overview') == 'overview' ? 'selected' : '' }}>Overview</option>
                            <option value="loans" {{ (isset($preferences['default_dashboard']) ? $preferences['default_dashboard'] : '') == 'loans' ? 'selected' : '' }}>Loans</option>
                            <option value="savings" {{ (isset($preferences['default_dashboard']) ? $preferences['default_dashboard'] : '') == 'savings' ? 'selected' : '' }}>Savings</option>
                            <option value="investments" {{ (isset($preferences['default_dashboard']) ? $preferences['default_dashboard'] : '') == 'investments' ? 'selected' : '' }}>Investments</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Widgets to Display</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="widgets[]" value="quick_stats" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ !isset($preferences['widgets']) || in_array('quick_stats', $preferences['widgets'] ?? []) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Quick Stats</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="widgets[]" value="recent_activity" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ !isset($preferences['widgets']) || in_array('recent_activity', $preferences['widgets'] ?? []) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Recent Activity</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="widgets[]" value="performance_metrics" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ !isset($preferences['widgets']) || in_array('performance_metrics', $preferences['widgets'] ?? []) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Performance Metrics</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="widgets[]" value="alerts" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]" {{ !isset($preferences['widgets']) || in_array('alerts', $preferences['widgets'] ?? []) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Alerts & Notifications</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Privacy Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Profile Visibility</p>
                            <p class="text-sm text-gray-600">Allow others to view your profile information</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="profile_visible" value="1" class="sr-only peer" {{ isset($preferences['profile_visible']) && $preferences['profile_visible'] ? 'checked' : 'checked' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Activity Status</p>
                            <p class="text-sm text-gray-600">Show when you're online or active</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_activity_status" value="1" class="sr-only peer" {{ isset($preferences['show_activity_status']) && $preferences['show_activity_status'] ? 'checked' : 'checked' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#015425] peer-focus:ring-opacity-20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#015425]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200">Account Information</h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Account Details</p>
                            <p class="text-sm text-blue-700 mt-1">To change your email or personal information, please visit the <a href="{{ route('admin.profile.edit') }}" class="underline font-medium">Edit Profile</a> page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('admin.profile.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection

