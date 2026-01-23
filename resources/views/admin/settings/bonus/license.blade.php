@extends('layouts.admin')

@section('page-title', 'License Management')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">License Management</h1>
        <p class="text-white text-opacity-90">Manage system license</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Key</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['license_key']->value ?? 'Not configured' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">License Type</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['license_type']->value ?? 'Standard' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $settings['license_expiry']->value ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="text-sm">
                        <span class="px-2 py-1 rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                </div>
            </div>
            <div class="pt-4 border-t">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Update License</button>
            </div>
        </div>
    </div>
</div>
@endsection

