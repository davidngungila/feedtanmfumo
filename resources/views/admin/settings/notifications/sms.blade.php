@extends('layouts.admin')

@section('page-title', 'SMS Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Settings</h1>
        <p class="text-white text-opacity-90">Configure SMS provider settings</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.sms-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                    <select name="sms_provider" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="twilio" {{ ($settings['sms_provider']->value ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                        <option value="nexmo" {{ ($settings['sms_provider']->value ?? '') === 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                        <option value="custom" {{ ($settings['sms_provider']->value ?? '') === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                        <input type="text" name="sms_api_key" value="{{ $settings['sms_api_key']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                        <input type="password" name="sms_api_secret" value="{{ $settings['sms_api_secret']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="sms_sender_id" value="{{ $settings['sms_sender_id']->value ?? '' }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="sms_enabled" value="1" 
                           {{ ($settings['sms_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <label class="ml-2 text-sm text-gray-700">Enable SMS Notifications</label>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

