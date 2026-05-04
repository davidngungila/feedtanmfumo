@extends('layouts.admin')

@section('page-title', 'View Email Template')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">View Email Template</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View email template details and configuration</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.settings.email-templates.edit', $templateType) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Template
                </a>
                <a href="{{ route('admin.settings.email-templates') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Templates
                </a>
            </div>
        </div>
    </div>

    <!-- Template Details -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[#015425]">{{ $templateName }}</h2>
                    <p class="text-gray-600">Email template for {{ $templateName }}</p>
                </div>
            </div>
        </div>

        <!-- Email Subject -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Subject</h3>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <p class="text-gray-800">{{ $subject }}</p>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <strong>Character Count:</strong> {{ strlen($subject) }}/255
                </div>
                <div class="flex items-center space-x-2">
                    @if(strlen($subject) > 200)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Approaching Limit</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Safe</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Email Body -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Body</h3>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $body }}</p>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <strong>Character Count:</strong> {{ strlen($body) }} characters
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">No Limit</span>
                </div>
            </div>
        </div>

        <!-- Email Template Variables -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Available Variables</h3>
                    <p class="text-sm text-blue-800 mb-2">You can use the following variables in this email template:</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{member_name}</code> - Member's full name
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{loan_amount}</code> - Loan amount
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{due_date}</code> - Payment due date
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{balance}</code> - Account balance
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{account_number}</code> - Account number
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <code class="bg-blue-200 px-1 rounded">{organization_name}</code> - Organization name
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('admin.settings.email-templates') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                Back to Templates
            </a>
            <a href="{{ route('admin.settings.email-templates.edit', $templateType) }}" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                Edit Template
            </a>
        </div>
    </div>
</div>
@endsection
