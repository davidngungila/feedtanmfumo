@extends('layouts.admin')

@section('page-title', 'View SMS Provider')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Provider Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $smsProvider->name }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.sms-provider.edit', $smsProvider) }}" class="inline-flex items-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    Edit
                </a>
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition font-medium">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Provider Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Provider Name</label>
                    <p class="text-sm text-gray-900 font-medium">{{ $smsProvider->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <div class="flex items-center space-x-2">
                        @if($smsProvider->active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                        @if($smsProvider->is_primary)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#015425] text-white">Primary</span>
                        @endif
                    </div>
                </div>

                @if($smsProvider->description)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                    <p class="text-sm text-gray-900">{{ $smsProvider->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- SMS Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">SMS Configuration</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Bearer Token (API Key)</label>
                    @if($smsProvider->username)
                    <p class="text-sm text-gray-900 font-mono break-all">{{ $smsProvider->username }}</p>
                    <p class="text-xs text-gray-500 mt-1">Length: {{ strlen($smsProvider->username) }} characters</p>
                    @else
                    <p class="text-sm text-red-600 font-medium">⚠️ Bearer token is missing! Please update the provider.</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">Stored in username field</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMS Password</label>
                    <p class="text-sm text-gray-500">••••••••</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMS From (Sender Name)</label>
                    <p class="text-sm text-gray-900">{{ $smsProvider->from ?? 'Not set' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMS API URL</label>
                    <p class="text-sm text-gray-900 break-all">{{ $smsProvider->api_url }}</p>
                </div>
            </div>
        </div>

        <!-- Test Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Send a test SMS to verify this provider configuration is working correctly.</p>
            
            <form action="{{ route('admin.sms-provider.test', $smsProvider) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="test_phone" value="{{ old('test_phone', '255712345678') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="255712345678" pattern="^255[0-9]{9}$">
                        <p class="text-xs text-gray-500 mt-1">Format: 255XXXXXXXXX (12 digits starting with 255)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Message</label>
                        <input type="text" name="test_message" value="{{ old('test_message', 'Test SMS from ' . $smsProvider->name . ' - Configuration test successful!') }}" maxlength="160" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Test message">
                        <p class="text-xs text-gray-500 mt-1">Max 160 characters</p>
                    </div>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Send Test SMS
                    </button>
                </div>
            </form>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Timestamps</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                    <p class="text-sm text-gray-900">{{ $smsProvider->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                    <p class="text-sm text-gray-900">{{ $smsProvider->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

