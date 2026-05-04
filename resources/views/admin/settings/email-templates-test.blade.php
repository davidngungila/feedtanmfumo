@extends('layouts.admin')

@section('page-title', 'Test Email Template')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Test Email Template</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Send a test email to verify template content and formatting</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.settings.email-templates.view', $templateType) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Template
                </a>
                <a href="{{ route('admin.settings.email-templates') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Templates
                </a>
            </div>
        </div>
    </div>

    <!-- Template Preview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Template Preview</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Email Subject</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-800">{{ $subject }}</div>
                        <div class="mt-2 text-xs text-gray-500">Character count: {{ strlen($subject) }}/255</div>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Email Body</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-800 whitespace-pre-wrap">{{ $body }}</div>
                        <div class="mt-2 text-xs text-gray-500">Character count: {{ strlen($body) }} characters</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.email-templates.send', $templateType) }}" method="POST">
        @csrf

        <!-- Test Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Test Configuration</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email_address" 
                            name="email_address" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                            placeholder="test@example.com"
                        >
                        <p class="text-xs text-gray-500 mt-1">Enter email address to receive test message</p>
                    </div>
                    <div>
                        <label for="member_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Member Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="member_name" 
                            name="member_name" 
                            required
                            maxlength="255"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                            placeholder="John Doe"
                        >
                        <p class="text-xs text-gray-500 mt-1">Name to use in the template</p>
                    </div>
                </div>

                <!-- Template Variables -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Template Variables (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="loan_amount" class="block text-sm font-medium text-gray-700 mb-2">Loan Amount</label>
                            <input 
                                type="text" 
                                id="loan_amount" 
                                name="test_variables[loan_amount]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                placeholder="100,000 TZS"
                            >
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input 
                                type="text" 
                                id="due_date" 
                                name="test_variables[due_date]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                placeholder="2024-12-31"
                            >
                        </div>
                        <div>
                            <label for="balance" class="block text-sm font-medium text-gray-700 mb-2">Balance</label>
                            <input 
                                type="text" 
                                id="balance" 
                                name="test_variables[balance]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                placeholder="50,000 TZS"
                            >
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                            <input 
                                type="text" 
                                id="account_number" 
                                name="test_variables[account_number]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                placeholder="ACC-001234"
                            >
                        </div>
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                            <input 
                                type="text" 
                                id="organization_name" 
                                name="test_variables[organization_name]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                placeholder="FeedTan CMG"
                            >
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">These variables will replace the placeholders in the template</p>
                </div>
            </div>
        </div>

        <!-- Available Variables Reference -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Available Variables</h3>
                    <p class="text-sm text-blue-800 mb-2">This template supports the following variables:</p>
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
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.settings.email-templates') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                        Cancel
                    </a>
                    <a href="{{ route('admin.settings.email-templates.view', $templateType) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                        View Template
                    </a>
                </div>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Send Test Email
                </button>
            </div>
        </div>
    </form>
</div>

@if(session('success'))
<div id="flash-success" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="flash-error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
@endif

<script>
// Auto-hide flash messages after 5 seconds
setTimeout(function() {
    const successMsg = document.getElementById('flash-success');
    const errorMsg = document.getElementById('flash-error');
    
    if (successMsg) {
        successMsg.remove();
    }
    if (errorMsg) {
        errorMsg.remove();
    }
}, 5000);
</script>
@endsection
