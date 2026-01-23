@extends('layouts.admin')

@section('page-title', 'Add SMS Provider')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Add SMS Provider</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure a new SMS gateway provider for system notifications</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.sms-provider.store') }}" method="POST" id="smsProviderForm">
                @csrf
                
                <div class="space-y-6">
                    <!-- Provider Configuration -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Configuration</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provider Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="provider_name" value="{{ old('name', 'Primary SMS Gateway') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="e.g., Primary SMS Gateway" required>
                                <p class="text-xs text-gray-500 mt-1">A descriptive name for this SMS provider</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bearer Token (API Key) <span class="text-red-500">*</span></label>
                                <input type="text" name="username" id="provider_username" value="{{ old('username') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="cedcce9becad866f59beac1fd5a235bc" required>
                                <p class="text-xs text-gray-500 mt-1">Bearer token for messaging-service.co.tz API (stored in username field)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMS Password (Optional)</label>
                                <input type="password" name="password" id="provider_password" value="{{ old('password') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Leave blank if not required">
                                <p class="text-xs text-gray-500 mt-1">Optional password if required by provider</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMS From (Sender Name)</label>
                                <input type="text" name="from" id="provider_from" value="{{ old('from', 'OfisiLink') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="OfisiLink" maxlength="11">
                                <p class="text-xs text-gray-500 mt-1">Name displayed as sender (if supported by gateway)</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMS API URL <span class="text-red-500">*</span></label>
                                <input type="url" name="api_url" id="provider_api_url" value="{{ old('api_url', 'https://messaging-service.co.tz/api/sms/v2/text/single') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="https://messaging-service.co.tz/api/sms/v2/text/single" required>
                                <p class="text-xs text-gray-500 mt-1">Full API endpoint URL (v2 endpoint recommended)</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" id="provider_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Optional description for this provider">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Optional description for this provider</p>
                            </div>

                            <div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="active" value="1" id="provider_active" {{ old('active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                    <label for="provider_active" class="text-sm font-medium text-gray-700">Active</label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Provider will be available for use</p>
                            </div>

                            <div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="is_primary" value="1" id="provider_is_primary" {{ old('is_primary', true) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                    <label for="provider_is_primary" class="text-sm font-medium text-gray-700">Set as Primary</label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Primary provider is used first</p>
                            </div>
                        </div>
                    </div>

                    <!-- Test Configuration -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Configuration</h3>
                        <p class="text-sm text-gray-600 mb-4">Test your configuration before saving to ensure everything works correctly.</p>
                        
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Test Phone Number</label>
                                    <input type="text" id="test_phone" value="255712345678" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="255712345678" pattern="^255[0-9]{9}$">
                                    <p class="text-xs text-gray-500 mt-1">Format: 255XXXXXXXXX (12 digits starting with 255)</p>
                                </div>

                                <div class="flex items-end">
                                    <button type="button" id="testConnectionBtn" class="w-full px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="testBtnText">Test Connection</span>
                                        <span id="testSpinner" class="hidden ml-2">
                                            <svg class="animate-spin h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Connection Status</label>
                                    <div id="connectionStatus" class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-md text-sm text-gray-600">
                                        Click "Test Connection" to check status
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                        Save Provider
                    </button>
                    <a href="{{ route('admin.settings.communication') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testBtn = document.getElementById('testConnectionBtn');
    const testBtnText = document.getElementById('testBtnText');
    const testSpinner = document.getElementById('testSpinner');
    const connectionStatus = document.getElementById('connectionStatus');
    
    testBtn.addEventListener('click', function() {
        const username = document.getElementById('provider_username').value;
        const password = document.getElementById('provider_password').value;
        const apiUrl = document.getElementById('provider_api_url').value;
        const from = document.getElementById('provider_from').value || 'FEEDTAN';
        const testPhone = document.getElementById('test_phone').value;
        
        if (!username || !password || !apiUrl || !testPhone) {
            connectionStatus.className = 'px-4 py-3 bg-red-50 border border-red-300 rounded-md text-sm text-red-800';
            connectionStatus.textContent = 'Please fill in all required fields (Username, Password, API URL, Test Phone)';
            return;
        }
        
        // Validate phone format
        if (!/^255[0-9]{9}$/.test(testPhone.replace(/[^0-9]/g, ''))) {
            connectionStatus.className = 'px-4 py-3 bg-red-50 border border-red-300 rounded-md text-sm text-red-800';
            connectionStatus.textContent = 'Invalid phone number format. Expected: 255XXXXXXXXX (12 digits starting with 255)';
            return;
        }
        
        // Clean phone number
        const cleanedPhone = testPhone.replace(/[^0-9]/g, '');
        
        testBtn.disabled = true;
        testBtnText.textContent = 'Testing...';
        testSpinner.classList.remove('hidden');
        connectionStatus.className = 'px-4 py-3 bg-blue-50 border border-blue-300 rounded-md text-sm text-blue-800';
        connectionStatus.textContent = 'Testing connection...';
        
        fetch('{{ route("admin.sms-provider.test-connection") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                username: username,
                password: password,
                api_url: apiUrl,
                from: from,
                test_phone: cleanedPhone
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                connectionStatus.className = 'px-4 py-3 bg-green-50 border border-green-300 rounded-md text-sm text-green-800';
                connectionStatus.textContent = data.message || 'Connection test successful!';
            } else {
                connectionStatus.className = 'px-4 py-3 bg-red-50 border border-red-300 rounded-md text-sm text-red-800';
                connectionStatus.textContent = data.error || 'Connection test failed. Please check your configuration.';
            }
        })
        .catch(error => {
            connectionStatus.className = 'px-4 py-3 bg-red-50 border border-red-300 rounded-md text-sm text-red-800';
            connectionStatus.textContent = 'Connection test failed: ' + error.message;
        })
        .finally(() => {
            testBtn.disabled = false;
            testBtnText.textContent = 'Test Connection';
            testSpinner.classList.add('hidden');
        });
    });
});
</script>
@endsection

