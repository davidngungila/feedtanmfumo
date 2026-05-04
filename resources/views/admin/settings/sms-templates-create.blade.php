@extends('layouts.admin')

@section('page-title', 'Create SMS Template')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Create New SMS Template</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Create a new SMS template for automated messaging</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.settings.sms-templates') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Templates
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.sms-templates.store') }}" method="POST">
        @csrf

        <!-- Template Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-[#015425] mb-4">Template Information</h2>
                <div class="space-y-4">
                    <div>
                        <label for="template_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="template_name" 
                            name="template_name" 
                            required
                            maxlength="255"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                            placeholder="e.g., Loan Rejection, Account Suspension, Birthday Greeting"
                        >
                        <p class="text-xs text-gray-500 mt-1">A descriptive name for this SMS template</p>
                    </div>

                    <div>
                        <label for="template_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Description
                        </label>
                        <input 
                            type="text" 
                            id="template_description" 
                            name="template_description" 
                            maxlength="255"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                            placeholder="e.g., Sent when loan application is rejected"
                        >
                        <p class="text-xs text-gray-500 mt-1">Brief description of when this template is used</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-[#015425] mb-4">Message Content</h2>
                <div class="space-y-4">
                    <div>
                        <label for="template_content" class="block text-sm font-medium text-gray-700 mb-2">
                            SMS Message Template <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="template_content" 
                            name="template_content" 
                            rows="6" 
                            maxlength="500" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] resize-none"
                            oninput="updateCharCount(this)"
                            placeholder="Enter your SMS message content here..."
                        ></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500">Maximum 500 characters (SMS limit)</p>
                            <div class="flex items-center space-x-2">
                                <span id="charCount" class="text-sm font-medium text-gray-900">0/500</span>
                                <span id="charStatus" class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Safe</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Variables -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Available Variables</h3>
                        <p class="text-sm text-blue-800 mb-2">You can use the following variables in this SMS template:</p>
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
                                <code class="bg-blue-200 px-1 rounded">{code}</code> - Verification code
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between">
                <a href="{{ route('admin.settings.sms-templates') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Create Template
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updateCharCount(textarea) {
    const charCount = textarea.value.length;
    const charCountElement = document.getElementById('charCount');
    const charStatusElement = document.getElementById('charStatus');
    
    charCountElement.textContent = charCount + '/500';
    
    // Update color based on character count
    if (charCount > 450) {
        charCountElement.className = 'text-sm font-medium text-red-600';
        charStatusElement.className = 'px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800';
        charStatusElement.textContent = 'Approaching Limit';
    } else if (charCount > 400) {
        charCountElement.className = 'text-sm font-medium text-yellow-600';
        charStatusElement.className = 'px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800';
        charStatusElement.textContent = 'Warning';
    } else {
        charCountElement.className = 'text-sm font-medium text-gray-900';
        charStatusElement.className = 'px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800';
        charStatusElement.textContent = 'Safe';
    }
}
</script>
@endsection
