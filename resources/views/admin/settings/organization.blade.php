@extends('layouts.admin')

@section('page-title', 'Organization Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Organization Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage organization information, contact details, and registration data</p>
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
        
        <form action="{{ route('admin.settings.organization.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Organization Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name *</label>
                            <input type="text" name="organization_name" value="{{ isset($settings['organization_name']) ? $settings['organization_name']->value : 'FeedTan Community Microfinance Group' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                            <input type="text" name="registration_number" value="{{ isset($settings['registration_number']) ? $settings['registration_number']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                            <input type="text" name="tax_id" value="{{ isset($settings['tax_id']) ? $settings['tax_id']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['address']) ? $settings['address']->value : '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ isset($settings['phone']) ? $settings['phone']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ isset($settings['email']) ? $settings['email']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" value="{{ isset($settings['website']) ? $settings['website']->value : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Founded Year</label>
                            <input type="number" name="founded_year" value="{{ isset($settings['founded_year']) ? $settings['founded_year']->value : '' }}" min="1900" max="{{ date('Y') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Members</label>
                            <input type="number" name="member_count" value="{{ isset($settings['member_count']) ? $settings['member_count']->value : '' }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ isset($settings['description']) ? $settings['description']->value : '' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Brief description about your organization</p>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Social Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Page</label>
                            <input type="url" name="facebook_url" value="{{ isset($settings['facebook_url']) ? $settings['facebook_url']->value : '' }}" placeholder="https://facebook.com/yourpage" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Handle</label>
                            <input type="text" name="twitter_handle" value="{{ isset($settings['twitter_handle']) ? $settings['twitter_handle']->value : '' }}" placeholder="@yourhandle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile</label>
                            <input type="url" name="linkedin_url" value="{{ isset($settings['linkedin_url']) ? $settings['linkedin_url']->value : '' }}" placeholder="https://linkedin.com/company/yourcompany" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" value="{{ isset($settings['whatsapp_number']) ? $settings['whatsapp_number']->value : '' }}" placeholder="+255XXXXXXXXX" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
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

