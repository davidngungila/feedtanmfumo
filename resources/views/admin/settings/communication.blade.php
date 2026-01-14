@extends('layouts.admin')

@section('page-title', 'Communication Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Communication Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure email and SMS communication settings</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        <form action="{{ route('admin.settings.communication.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Email Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Email Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                            <select name="mail_mailer" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="smtp" {{ (isset($settings['mail_mailer']) ? $settings['mail_mailer']->value : 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ (isset($settings['mail_mailer']) ? $settings['mail_mailer']->value : '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="mail_host" value="{{ isset($settings['mail_host']) ? $settings['mail_host']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="number" name="mail_port" value="{{ isset($settings['mail_port']) ? $settings['mail_port']->value : '587' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="mail_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="tls" {{ (isset($settings['mail_encryption']) ? $settings['mail_encryption']->value : 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ (isset($settings['mail_encryption']) ? $settings['mail_encryption']->value : '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                            <input type="text" name="mail_username" value="{{ isset($settings['mail_username']) ? $settings['mail_username']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                            <input type="password" name="mail_password" value="{{ isset($settings['mail_password']) ? $settings['mail_password']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                            <input type="email" name="mail_from_address" value="{{ isset($settings['mail_from_address']) ? $settings['mail_from_address']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ isset($settings['mail_from_name']) ? $settings['mail_from_name']->value : 'FEEDTAN DIGITAL' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">SMS Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                            <select name="sms_provider" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="twilio" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : 'twilio') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="africas_talking" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : '') == 'africas_talking' ? 'selected' : '' }}>Africa's Talking</option>
                                <option value="custom" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : '') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS Enabled</label>
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" name="sms_enabled" value="1" id="sms_enabled" {{ (isset($settings['sms_enabled']) && $settings['sms_enabled']->value) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                <label for="sms_enabled" class="text-sm font-medium text-gray-700">Enable SMS Notifications</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS API Key</label>
                            <input type="text" name="sms_api_key" value="{{ isset($settings['sms_api_key']) ? $settings['sms_api_key']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS API Secret</label>
                            <input type="password" name="sms_api_secret" value="{{ isset($settings['sms_api_secret']) ? $settings['sms_api_secret']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                            <input type="text" name="sms_sender_id" value="{{ isset($settings['sms_sender_id']) ? $settings['sms_sender_id']->value : 'FEEDTAN DIGITAL' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

