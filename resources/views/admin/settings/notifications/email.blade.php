@extends('layouts.admin')

@section('page-title', 'Email Settings (SMTP)')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Email Settings (SMTP)</h1>
        <p class="text-white text-opacity-90">Configure SMTP server for email delivery</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.email-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailer</label>
                    <select name="mail_mailer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="smtp" {{ ($settings['mail_mailer']->value ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendmail" {{ ($settings['mail_mailer']->value ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" name="mail_port" value="{{ $settings['mail_port']->value ?? '587' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                        <input type="password" name="mail_password" value="{{ $settings['mail_password']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                        <select name="mail_encryption" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="tls" {{ ($settings['mail_encryption']->value ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption']->value ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name']->value ?? '' }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
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

