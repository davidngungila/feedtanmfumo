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
                    
                    <!-- Quick Setup Presets -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Quick Setup Presets</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <button type="button" onclick="setGmailConfig()" class="px-3 py-2 bg-white border border-blue-300 rounded-md text-sm text-blue-700 hover:bg-blue-50 transition">
                                Gmail SMTP
                            </button>
                            <button type="button" onclick="setOutlookConfig()" class="px-3 py-2 bg-white border border-blue-300 rounded-md text-sm text-blue-700 hover:bg-blue-50 transition">
                                Outlook/Hotmail
                            </button>
                            <button type="button" onclick="setCustomConfig()" class="px-3 py-2 bg-white border border-blue-300 rounded-md text-sm text-blue-700 hover:bg-blue-50 transition">
                                Custom SMTP
                            </button>
                        </div>
                        <p class="text-xs text-blue-700 mt-2">
                            <strong>Note:</strong> For Gmail, you need to use an "App Password" instead of your regular password. 
                            Enable 2-Step Verification and generate an App Password from your Google Account settings.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                            <select name="mail_mailer" id="mail_mailer" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="smtp" {{ (isset($settings['mail_mailer']) ? $settings['mail_mailer']->value : 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ (isset($settings['mail_mailer']) ? $settings['mail_mailer']->value : '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="mail_host" id="mail_host" value="{{ isset($settings['mail_host']) ? $settings['mail_host']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="smtp.gmail.com">
                            <p class="text-xs text-gray-500 mt-1">Gmail: smtp.gmail.com | Outlook: smtp-mail.outlook.com</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="number" name="mail_port" id="mail_port" value="{{ isset($settings['mail_port']) ? $settings['mail_port']->value : '587' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <p class="text-xs text-gray-500 mt-1">TLS: 587 | SSL: 465</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                            <select name="mail_encryption" id="mail_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="tls" {{ (isset($settings['mail_encryption']) ? $settings['mail_encryption']->value : 'tls') == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                                <option value="ssl" {{ (isset($settings['mail_encryption']) ? $settings['mail_encryption']->value : '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                            <input type="text" name="mail_username" id="mail_username" value="{{ isset($settings['mail_username']) ? $settings['mail_username']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="your-email@gmail.com">
                            <p class="text-xs text-gray-500 mt-1">Your full email address</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                            <input type="password" name="mail_password" id="mail_password" value="{{ isset($settings['mail_password']) ? $settings['mail_password']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="App Password for Gmail">
                            <p class="text-xs text-gray-500 mt-1">For Gmail: Use App Password (not your regular password)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="mail_primary_email" id="mail_primary_email" value="{{ isset($settings['mail_primary_email']) ? $settings['mail_primary_email']->value : (isset($settings['mail_from_address']) ? $settings['mail_from_address']->value : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="primary@feedtan.com" required>
                            <p class="text-xs text-gray-500 mt-1">Primary email address used for sending emails</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Address (Legacy)</label>
                            <input type="email" name="mail_from_address" id="mail_from_address" value="{{ isset($settings['mail_from_address']) ? $settings['mail_from_address']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="noreply@feedtan.com">
                            <p class="text-xs text-gray-500 mt-1">Email address that will appear as sender (fallback)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Email Addresses</label>
                            <textarea name="mail_additional_emails" id="mail_additional_emails" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="email1@feedtan.com, email2@feedtan.com">{{ isset($settings['mail_additional_emails']) ? $settings['mail_additional_emails']->value : '' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Comma-separated list of additional email addresses (for notifications, CC, etc.)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                            <input type="text" name="mail_from_name" id="mail_from_name" value="{{ isset($settings['mail_from_name']) ? $settings['mail_from_name']->value : 'FeedTan Community Microfinance Group' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <p class="text-xs text-gray-500 mt-1">Name that will appear as sender</p>
                        </div>
                    </div>
                    
                    <script>
                        function setGmailConfig() {
                            document.getElementById('mail_host').value = 'smtp.gmail.com';
                            document.getElementById('mail_port').value = '587';
                            document.getElementById('mail_encryption').value = 'tls';
                            document.getElementById('mail_mailer').value = 'smtp';
                        }
                        
                        function setOutlookConfig() {
                            document.getElementById('mail_host').value = 'smtp-mail.outlook.com';
                            document.getElementById('mail_port').value = '587';
                            document.getElementById('mail_encryption').value = 'tls';
                            document.getElementById('mail_mailer').value = 'smtp';
                        }
                        
                        function setCustomConfig() {
                            document.getElementById('mail_host').value = '';
                            document.getElementById('mail_port').value = '587';
                            document.getElementById('mail_encryption').value = 'tls';
                            document.getElementById('mail_mailer').value = 'smtp';
                        }
                    </script>
                    
                    <!-- Organization Information for Email Headers -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Organization Information (Used in Email Headers)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                                <input type="text" name="organization_name" value="{{ isset($settings['organization_name']) ? $settings['organization_name']->value : 'FeedTan Community Microfinance Group' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">P.O. Box</label>
                                <input type="text" name="organization_po_box" value="{{ isset($settings['organization_po_box']) ? $settings['organization_po_box']->value : 'P.O.Box 7744' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                <input type="text" name="organization_address" value="{{ isset($settings['organization_address']) ? $settings['organization_address']->value : 'Ushirika Sokoine Road' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" name="organization_city" value="{{ isset($settings['organization_city']) ? $settings['organization_city']->value : 'Moshi' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                <input type="text" name="organization_region" value="{{ isset($settings['organization_region']) ? $settings['organization_region']->value : 'Kilimanjaro' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input type="text" name="organization_country" value="{{ isset($settings['organization_country']) ? $settings['organization_country']->value : 'Tanzania' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            </div>
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
                                <option value="custom" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : 'custom') == 'custom' ? 'selected' : '' }}>Custom</option>
                                <option value="twilio" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="africas_talking" {{ (isset($settings['sms_provider']) ? $settings['sms_provider']->value : '') == 'africas_talking' ? 'selected' : '' }}>Africa's Talking</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS API Key (Bearer Token) <span class="text-red-500">*</span></label>
                            <input type="text" name="sms_api_key" value="{{ isset($settings['sms_api_key']) ? $settings['sms_api_key']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="f9a89f439206e27169ead766463ca92c" required>
                            <p class="text-xs text-gray-500 mt-1">Bearer token for messaging-service.co.tz API</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS API Secret (Optional)</label>
                            <input type="password" name="sms_api_secret" value="{{ isset($settings['sms_api_secret']) ? $settings['sms_api_secret']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            <p class="text-xs text-gray-500 mt-1">API secret if required by provider</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SMS Sender ID <span class="text-red-500">*</span></label>
                            <input type="text" name="sms_sender_id" value="{{ isset($settings['sms_sender_id']) ? $settings['sms_sender_id']->value : 'FEEDTAN' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="FEEDTAN" maxlength="11" required>
                            <p class="text-xs text-gray-500 mt-1">Sender name (max 11 characters) that appears on recipient's phone</p>
                        </div>
                    </div>
                </div>

                <!-- SMS Provider Management -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">SMS Provider Management</h3>
                        <a href="{{ route('admin.sms-provider.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                            Add SMS Provider
                        </a>
                    </div>
                    
                    @if($providers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Provider Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">From</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">API URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Primary</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($providers as $provider)
                                <tr class="hover:bg-gray-50 {{ $provider->is_primary ? 'bg-green-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $provider->name }}</div>
                                        @if($provider->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($provider->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $provider->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $provider->from ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $provider->api_url }}">{{ $provider->api_url }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($provider->active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($provider->is_primary)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#015425] text-white">Primary</span>
                                        @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if(!$provider->is_primary)
                                            <form action="{{ route('admin.sms-provider.set-primary', $provider) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900" title="Set as Primary">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route('admin.sms-provider.edit', $provider) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @if(!$provider->is_primary)
                                            <form action="{{ route('admin.sms-provider.destroy', $provider) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this SMS provider?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">No SMS provider configured. <a href="{{ route('admin.sms-provider.create') }}" class="underline font-medium">Add one now</a> to enable SMS notifications.</p>
                    </div>
                    @endif
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
        
        <!-- Test Email Section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Email Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Send a test email to verify your email settings are working correctly.</p>
            
            @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-4">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
            @endif
            
            <form action="{{ route('admin.settings.communication.test-email') }}" method="POST">
                @csrf
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                        <input type="email" name="test_email" value="davidngungila@gmail.com" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Send Test Email
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Test SMS Section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test SMS Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Send a test SMS to verify your SMS settings are working correctly.</p>
            
            @if(session('sms_success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-4">
                <p class="text-sm text-green-800">{{ session('sms_success') }}</p>
            </div>
            @endif
            
            @if(session('sms_error'))
            <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-sm text-red-800">{{ session('sms_error') }}</p>
            </div>
            @endif
            
            <form action="{{ route('admin.settings.communication.test-sms') }}" method="POST" id="testSmsForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="test_phone" id="test_phone" value="{{ isset($settings['organization_phone']) ? preg_replace('/[^0-9]/', '', $settings['organization_phone']->value) : '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="255612345678 or 0612345678">
                        <p class="text-xs text-gray-500 mt-1">Enter phone number with country code (255) or local format (0)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Test Message</label>
                        <input type="text" name="test_message" id="test_message" value="Test SMS from FeedTan - SMS gateway is working correctly!" maxlength="160" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Test message">
                        <p class="text-xs text-gray-500 mt-1">Max 160 characters</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" id="testSmsBtn" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="testSmsBtnText">Send Test SMS</span>
                        <span id="testSmsSpinner" class="hidden ml-2">
                            <svg class="animate-spin h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <p id="testSmsStatus" class="text-sm hidden"></p>
                </div>
            </form>
            
            <script>
                document.getElementById('testSmsForm').addEventListener('submit', function(e) {
                    const btn = document.getElementById('testSmsBtn');
                    const btnText = document.getElementById('testSmsBtnText');
                    const spinner = document.getElementById('testSmsSpinner');
                    const status = document.getElementById('testSmsStatus');
                    
                    btn.disabled = true;
                    btnText.textContent = 'Sending...';
                    spinner.classList.remove('hidden');
                    status.classList.add('hidden');
                });
            </script>
        </div>
    </div>
</div>
</div>
@endsection

