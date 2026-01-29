@extends('layouts.admin')

@section('page-title', 'SMS Log Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Log Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Complete details of SMS communication</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.sms.logs') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Logs
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Status Badge -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Status</h3>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $smsLog->status_badge }}">
                        {{ $smsLog->status_group_name ?? 'Unknown' }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">SMS Count</p>
                    <p class="text-2xl font-bold text-[#015425]">{{ $smsLog->sms_count ?? 1 }}</p>
                </div>
            </div>
        </div>

        <!-- Message Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Message Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">From (Sender ID)</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->from ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">To (Phone Number)</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->to }}</p>
                    @if($smsLog->user)
                    <p class="text-xs text-gray-400 mt-1">{{ $smsLog->user->name }} ({{ $smsLog->user->email }})</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Channel</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->channel ?? 'Internet SMS' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Reference</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->reference ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Message ID</p>
                    <p class="text-sm font-medium text-gray-900 font-mono">{{ $smsLog->message_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Success</p>
                    <p class="text-sm font-medium {{ $smsLog->success ? 'text-green-600' : 'text-red-600' }}">
                        {{ $smsLog->success ? 'Yes' : 'No' }}
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500 mb-1">Message Content</p>
                <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                    @if($smsLog->message)
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $smsLog->message }}</p>
                    @else
                    <p class="text-sm text-gray-400 italic">No message content available. This log was likely synced from the API, which doesn't include message content.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Timestamps</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Send At</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $smsLog->sent_at ? $smsLog->sent_at->format('Y-m-d H:i:s') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Done At</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $smsLog->done_at ? $smsLog->done_at->format('Y-m-d H:i:s') : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Initiated At</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $smsLog->created_at->format('Y-m-d H:i:s') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $smsLog->updated_at->format('Y-m-d H:i:s') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        @if($smsLog->status_name || $smsLog->status_description)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Information</h3>
            <div class="space-y-2">
                @if($smsLog->status_name)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status Name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->status_name }}</p>
                </div>
                @endif
                @if($smsLog->status_id)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status ID</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->status_id }}</p>
                </div>
                @endif
                @if($smsLog->status_description)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-sm text-gray-900">{{ $smsLog->status_description }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($smsLog->sentByUser)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Sent By</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->sentByUser->name }} ({{ $smsLog->sentByUser->email }})</p>
                </div>
                @endif
                @if($smsLog->template_id)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Template ID</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->template_id }}</p>
                </div>
                @endif
                @if($smsLog->saving_behavior)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Saving Behavior</p>
                    <p class="text-sm font-medium text-gray-900">{{ $smsLog->saving_behavior }}</p>
                </div>
                @endif
                @if($smsLog->error_message)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Error Message</p>
                    <p class="text-sm text-red-600 bg-red-50 p-3 rounded-md">{{ $smsLog->error_message }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- API Response -->
        @if($smsLog->api_response)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">API Response</h3>
            <div class="bg-gray-50 rounded-md p-4 border border-gray-200 overflow-x-auto">
                <pre class="text-xs text-gray-700">{{ json_encode($smsLog->api_response, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif

        <!-- Delivery Information -->
        @if($smsLog->delivery)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delivery Information</h3>
            <div class="bg-gray-50 rounded-md p-4 border border-gray-200 overflow-x-auto">
                <pre class="text-xs text-gray-700">{{ json_encode($smsLog->delivery, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

