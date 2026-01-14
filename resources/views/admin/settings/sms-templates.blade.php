@extends('layouts.admin')

@section('page-title', 'SMS Templates')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Templates</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage SMS message templates for notifications and alerts</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.sms-templates.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">SMS Template Variables</h3>
                    <p class="text-sm text-blue-800 mb-2">You can use the following variables in your SMS templates:</p>
                    <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                        <li><code>{member_name}</code> - Member's full name</li>
                        <li><code>{loan_amount}</code> - Loan amount</li>
                        <li><code>{due_date}</code> - Payment due date</li>
                        <li><code>{balance}</code> - Account balance</li>
                        <li><code>{account_number}</code> - Account number</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Loan Approval SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Loan Approval SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_loan_approval" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_loan_approval']) ? $settings['sms_loan_approval']->value : 'Dear {member_name}, your loan application of {loan_amount} TZS has been approved. Please visit our office to complete the process. - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Loan Disbursement SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Loan Disbursement SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_loan_disbursement" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_loan_disbursement']) ? $settings['sms_loan_disbursement']->value : 'Dear {member_name}, your loan of {loan_amount} TZS has been disbursed to your account. - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Payment Reminder SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Payment Reminder SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_payment_reminder" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_payment_reminder']) ? $settings['sms_payment_reminder']->value : 'Dear {member_name}, this is a reminder that your payment of {loan_amount} TZS is due on {due_date}. Please make payment to avoid penalties. - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Payment Confirmation SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Payment Confirmation SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_payment_confirmation" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_payment_confirmation']) ? $settings['sms_payment_confirmation']->value : 'Dear {member_name}, we have received your payment of {loan_amount} TZS. Your current balance is {balance} TZS. Thank you! - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Password Reset SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Password Reset SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_password_reset" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_password_reset']) ? $settings['sms_password_reset']->value : 'Dear {member_name}, your password reset code is {code}. Use this code to reset your password. Do not share this code with anyone. - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Welcome Message SMS -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Welcome Message SMS</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                <textarea name="sms_welcome_message" rows="4" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['sms_welcome_message']) ? $settings['sms_welcome_message']->value : 'Welcome {member_name} to FeedTan CMG! Your account {account_number} has been created successfully. We are here to support your financial growth. - FeedTan CMG' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters (SMS limit)</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save SMS Templates
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

