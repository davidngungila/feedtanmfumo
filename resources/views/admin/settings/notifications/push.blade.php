@extends('layouts.admin')

@section('page-title', 'Push Notifications')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Push Notifications</h1>
        <p class="text-white text-opacity-90">Configure push notification settings</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.push-notifications.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="flex items-center">
                    <input type="checkbox" name="push_enabled" value="1" 
                           {{ ($settings['push_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <label class="ml-2 text-sm text-gray-700">Enable Push Notifications</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Firebase Server Key</label>
                    <input type="text" name="push_firebase_key" value="{{ $settings['push_firebase_key']->value ?? '' }}" 
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

