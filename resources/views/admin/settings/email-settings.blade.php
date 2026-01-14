@extends('layouts.admin')

@section('page-title', 'Email Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Email Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure email templates and SMTP settings</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Communication Settings
                </a>
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.email-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Email Template Variables</h3>
                    <p class="text-sm text-blue-800 mb-2">You can use the following variables in your email templates:</p>
                    <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                        <li><code>{member_name}</code> - Member's full name</li>
                        <li><code>{loan_amount}</code> - Loan amount</li>
                        <li><code>{due_date}</code> - Payment due date</li>
                        <li><code>{balance}</code> - Account balance</li>
                        <li><code>{account_number}</code> - Account number</li>
                        <li><code>{organization_name}</code> - Organization name</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Loan Approval Email -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Loan Approval Email</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Subject</label>
                    <input type="text" name="email_loan_approval_subject" value="{{ isset($settings['email_loan_approval_subject']) ? $settings['email_loan_approval_subject']->value : 'Loan Application Approved - FeedTan CMG' }}" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Body</label>
                    <textarea name="email_loan_approval_body" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['email_loan_approval_body']) ? $settings['email_loan_approval_body']->value : 'Dear {member_name},

We are pleased to inform you that your loan application of {loan_amount} TZS has been approved.

Please visit our office within 7 days to complete the loan processing and disbursement.

If you have any questions, please contact us.

Best regards,
{organization_name}' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Payment Reminder Email -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Payment Reminder Email</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Subject</label>
                    <input type="text" name="email_payment_reminder_subject" value="{{ isset($settings['email_payment_reminder_subject']) ? $settings['email_payment_reminder_subject']->value : 'Payment Reminder - {due_date}' }}" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Body</label>
                    <textarea name="email_payment_reminder_body" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['email_payment_reminder_body']) ? $settings['email_payment_reminder_body']->value : 'Dear {member_name},

This is a friendly reminder that your payment of {loan_amount} TZS is due on {due_date}.

Your current balance is {balance} TZS.

Please make payment before the due date to avoid penalties and maintain your good standing with us.

Thank you for your prompt attention to this matter.

Best regards,
{organization_name}' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Welcome Email -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Welcome Email</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Subject</label>
                    <input type="text" name="email_welcome_subject" value="{{ isset($settings['email_welcome_subject']) ? $settings['email_welcome_subject']->value : 'Welcome to {organization_name}' }}" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Body</label>
                    <textarea name="email_welcome_body" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['email_welcome_body']) ? $settings['email_welcome_body']->value : 'Dear {member_name},

Welcome to {organization_name}!

Your account has been successfully created with account number: {account_number}

We are delighted to have you as a member of our community. Our mission is to support your financial growth and prosperity.

You can now access your account online and manage your savings, loans, and investments.

If you have any questions or need assistance, please do not hesitate to contact us.

Welcome aboard!

Best regards,
{organization_name}' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save Email Templates
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

