@extends('layouts.admin')

@section('page-title', 'Organization Profile')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Organization Profile</h1>
        <p class="text-white text-opacity-90">Manage organization information</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.organization-profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                        <input type="text" name="organization_name" value="{{ $settings['organization_name']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                        <input type="text" name="registration_number" value="{{ $settings['registration_number']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">{{ $settings['description']->value ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Founded Year</label>
                        <input type="number" name="founded_year" value="{{ $settings['founded_year']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member Count</label>
                        <input type="number" name="member_count" value="{{ $settings['member_count']->value ?? '' }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
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

