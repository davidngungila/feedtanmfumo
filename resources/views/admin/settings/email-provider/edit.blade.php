@extends('layouts.admin')

@section('page-title', 'Edit Email Provider')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Email Provider</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Update email gateway provider configuration</p>
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
            <form action="{{ route('admin.email-provider.update', $emailProvider) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Provider Configuration -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Configuration</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provider Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $emailProvider->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="e.g., Primary Email Gateway" required>
                                <p class="text-xs text-gray-500 mt-1">A descriptive name for this email provider</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mail Driver <span class="text-red-500">*</span></label>
                                <select name="mailer" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                                    <option value="smtp" {{ old('mailer', $emailProvider->mailer) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ old('mailer', $emailProvider->mailer) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host <span class="text-red-500">*</span></label>
                                <input type="text" name="host" value="{{ old('host', $emailProvider->host) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="smtp.gmail.com" required>
                                <p class="text-xs text-gray-500 mt-1">Gmail: smtp.gmail.com | Outlook: smtp-mail.outlook.com</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port <span class="text-red-500">*</span></label>
                                <input type="number" name="port" value="{{ old('port', $emailProvider->port) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" min="1" max="65535" required>
                                <p class="text-xs text-gray-500 mt-1">TLS: 587 | SSL: 465</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Encryption <span class="text-red-500">*</span></label>
                                <select name="encryption" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                                    <option value="tls" {{ old('encryption', $emailProvider->encryption) == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                                    <option value="ssl" {{ old('encryption', $emailProvider->encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                <input type="text" name="username" value="{{ old('username', $emailProvider->username) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="your-email@gmail.com">
                                <p class="text-xs text-gray-500 mt-1">Your full email address</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                <input type="password" name="password" value="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Leave blank to keep current password">
                                <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="from_address" value="{{ old('from_address', $emailProvider->from_address) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="noreply@feedtan.com" required>
                                <p class="text-xs text-gray-500 mt-1">Email address that will appear as sender</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text" name="from_name" value="{{ old('from_name', $emailProvider->from_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="FeedTan CMG">
                                <p class="text-xs text-gray-500 mt-1">Name that will appear as sender</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Optional description for this provider">{{ old('description', $emailProvider->description) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Optional description for this provider</p>
                            </div>

                            <div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="active" value="1" id="provider_active" {{ old('active', $emailProvider->active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                    <label for="provider_active" class="text-sm font-medium text-gray-700">Active</label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Provider will be available for use</p>
                            </div>

                            <div>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="is_primary" value="1" id="provider_is_primary" {{ old('is_primary', $emailProvider->is_primary) ? 'checked' : '' }} class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                    <label for="provider_is_primary" class="text-sm font-medium text-gray-700">Set as Primary</label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Primary provider is used first</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                        Update Provider
                    </button>
                    <a href="{{ route('admin.settings.communication') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

