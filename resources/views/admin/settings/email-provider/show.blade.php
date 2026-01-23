@extends('layouts.admin')

@section('page-title', 'View Email Provider')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Email Provider Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $emailProvider->name }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.email-provider.edit', $emailProvider) }}" class="inline-flex items-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
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
                    <p class="text-sm text-gray-900 font-medium">{{ $emailProvider->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <div class="flex items-center space-x-2">
                        @if($emailProvider->active)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                        @if($emailProvider->is_primary)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#015425] text-white">Primary</span>
                        @endif
                    </div>
                </div>

                @if($emailProvider->description)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- SMTP Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">SMTP Configuration</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Mail Driver</label>
                    <p class="text-sm text-gray-900 uppercase">{{ $emailProvider->mailer }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMTP Host</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->host }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMTP Port</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->port }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Encryption</label>
                    <p class="text-sm text-gray-900 uppercase">{{ $emailProvider->encryption }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMTP Username</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->username ?? 'Not set' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">SMTP Password</label>
                    <p class="text-sm text-gray-500">••••••••</p>
                </div>
            </div>
        </div>

        <!-- From Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">From Configuration</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">From Email Address</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->from_address }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">From Name</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->from_name ?? 'Not set' }}</p>
                </div>
            </div>
        </div>

        <!-- Test Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Send a test email to verify this provider configuration is working correctly.</p>
            
            <form action="{{ route('admin.email-provider.test', $emailProvider) }}" method="POST">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="test_email" value="{{ old('test_email', 'davidngungila@gmail.com') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Send Test Email
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Timestamps</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                    <p class="text-sm text-gray-900">{{ $emailProvider->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

