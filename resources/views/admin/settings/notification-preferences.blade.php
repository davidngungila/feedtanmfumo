@extends('layouts.admin')

@section('page-title', 'Notification Preferences')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Notification Preferences</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure notification channels and preferences for system alerts</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.notification-preferences.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Notification Channels -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Notification Channels
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Notification Channels</label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notification_channels[]" value="email" id="channel_email" {{ (isset($settings['notification_channels']) && in_array('email', json_decode($settings['notification_channels']->value ?? '[]', true))) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            <label for="channel_email" class="text-sm font-medium text-gray-700">Email Notifications</label>
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notification_channels[]" value="sms" id="channel_sms" {{ (isset($settings['notification_channels']) && in_array('sms', json_decode($settings['notification_channels']->value ?? '[]', true))) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            <label for="channel_sms" class="text-sm font-medium text-gray-700">SMS Notifications</label>
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="notification_channels[]" value="push" id="channel_push" {{ (isset($settings['notification_channels']) && in_array('push', json_decode($settings['notification_channels']->value ?? '[]', true))) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            <label for="channel_push" class="text-sm font-medium text-gray-700">Push Notifications (Browser)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Loan Notifications
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_loan_approval" class="text-sm font-medium text-gray-700">Loan Approval Notifications</label>
                        <p class="text-xs text-gray-500 mt-1">Notify members when their loan application is approved</p>
                    </div>
                    <input type="checkbox" name="notify_loan_approval" value="1" id="notify_loan_approval" {{ (isset($settings['notify_loan_approval']) && $settings['notify_loan_approval']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_loan_disbursement" class="text-sm font-medium text-gray-700">Loan Disbursement Notifications</label>
                        <p class="text-xs text-gray-500 mt-1">Notify members when loan funds are disbursed</p>
                    </div>
                    <input type="checkbox" name="notify_loan_disbursement" value="1" id="notify_loan_disbursement" {{ (isset($settings['notify_loan_disbursement']) && $settings['notify_loan_disbursement']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
            </div>
        </div>

        <!-- Payment Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Payment Notifications
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_payment_due" class="text-sm font-medium text-gray-700">Payment Due Reminders</label>
                        <p class="text-xs text-gray-500 mt-1">Send reminders when payments are due</p>
                    </div>
                    <input type="checkbox" name="notify_payment_due" value="1" id="notify_payment_due" {{ (isset($settings['notify_payment_due']) && $settings['notify_payment_due']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_payment_received" class="text-sm font-medium text-gray-700">Payment Received Confirmations</label>
                        <p class="text-xs text-gray-500 mt-1">Notify members when payments are received</p>
                    </div>
                    <input type="checkbox" name="notify_payment_received" value="1" id="notify_payment_received" {{ (isset($settings['notify_payment_received']) && $settings['notify_payment_received']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
            </div>
        </div>

        <!-- Account Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Account Notifications
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_account_created" class="text-sm font-medium text-gray-700">Account Created Notifications</label>
                        <p class="text-xs text-gray-500 mt-1">Send welcome message when new accounts are created</p>
                    </div>
                    <input type="checkbox" name="notify_account_created" value="1" id="notify_account_created" {{ (isset($settings['notify_account_created']) && $settings['notify_account_created']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label for="notify_password_changed" class="text-sm font-medium text-gray-700">Password Changed Notifications</label>
                        <p class="text-xs text-gray-500 mt-1">Notify users when their password is changed</p>
                    </div>
                    <input type="checkbox" name="notify_password_changed" value="1" id="notify_password_changed" {{ (isset($settings['notify_password_changed']) && $settings['notify_password_changed']->value) ? 'checked' : 'checked' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save Notification Preferences
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

