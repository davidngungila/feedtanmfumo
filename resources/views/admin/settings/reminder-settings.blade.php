@extends('layouts.admin')

@section('page-title', 'Reminder Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Reminder Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure automated reminders for loans and payments</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.reminder-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Reminder Settings -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Payment Reminder Configuration
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="reminder_enabled" class="text-sm font-medium text-gray-700">Enable Payment Reminders</label>
                        <p class="text-xs text-gray-500 mt-1">Automatically send reminders for upcoming payments</p>
                    </div>
                    <input type="checkbox" name="reminder_enabled" value="1" id="reminder_enabled" {{ (isset($settings['reminder_enabled']) && $settings['reminder_enabled']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Days Before Due Date</label>
                        <input type="number" name="reminder_days_before_due" value="{{ isset($settings['reminder_days_before_due']) ? $settings['reminder_days_before_due']->value : '3' }}" min="0" max="30" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <p class="text-xs text-gray-500 mt-1">Send reminders X days before payment due date</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reminder Frequency</label>
                        <select name="reminder_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                            <option value="daily" {{ (isset($settings['reminder_frequency']) ? $settings['reminder_frequency']->value : 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ (isset($settings['reminder_frequency']) ? $settings['reminder_frequency']->value : '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">How often to send reminders</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reminder Time</label>
                        <input type="time" name="reminder_time" value="{{ isset($settings['reminder_time']) ? $settings['reminder_time']->value : '09:00' }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <p class="text-xs text-gray-500 mt-1">Time of day to send reminders</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Reminder Settings -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Overdue Payment Reminders
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                    <div>
                        <label for="overdue_reminder_enabled" class="text-sm font-medium text-gray-700">Enable Overdue Reminders</label>
                        <p class="text-xs text-gray-500 mt-1">Send reminders for payments that are overdue</p>
                    </div>
                    <input type="checkbox" name="overdue_reminder_enabled" value="1" id="overdue_reminder_enabled" {{ (isset($settings['overdue_reminder_enabled']) && $settings['overdue_reminder_enabled']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overdue Reminder Frequency</label>
                    <select name="overdue_reminder_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <option value="daily" {{ (isset($settings['overdue_reminder_frequency']) ? $settings['overdue_reminder_frequency']->value : 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ (isset($settings['overdue_reminder_frequency']) ? $settings['overdue_reminder_frequency']->value : '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">How often to send overdue reminders</p>
                </div>
            </div>
        </div>

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Reminder System Information</h3>
                    <p class="text-sm text-blue-800 mb-2">The reminder system will automatically:</p>
                    <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                        <li>Send payment reminders X days before the due date</li>
                        <li>Send overdue reminders for payments that are past due</li>
                        <li>Use the configured notification channels (Email, SMS)</li>
                        <li>Respect the frequency and time settings</li>
                        <li>Only send reminders for active loans with outstanding balances</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save Reminder Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

