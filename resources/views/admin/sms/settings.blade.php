@extends('layouts.admin')

@section('page-title', 'SMS Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Settings</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage SMS configuration settings</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.sms.send') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-30 transition font-medium">
                    Send SMS
                </a>
                <a href="{{ route('admin.sms.templates') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-30 transition font-medium">
                    Templates
                </a>
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Communication
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <form action="{{ route('admin.sms.settings.store') }}" method="POST" class="space-y-6">
            @csrf

            @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $category }}</h3>
                
                <div class="space-y-4">
                    @foreach($settingsByCategory[$category] as $setting)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start border-b pb-4 last:border-0 last:pb-0">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Key
                            </label>
                            <input type="text" 
                                   name="settings[{{ $setting->id }}][key]" 
                                   value="{{ $setting->key }}"
                                   required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Value
                            </label>
                            <input type="text" 
                                   name="settings[{{ $setting->id }}][value]" 
                                   value="{{ $setting->value }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <input type="text" 
                                   name="settings[{{ $setting->id }}][description]" 
                                   value="{{ $setting->description }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] text-sm">
                        </div>
                        <div class="md:col-span-1">
                            <input type="hidden" name="settings[{{ $setting->id }}][category]" value="{{ $setting->category }}">
                            <button type="button" 
                                    onclick="deleteSetting({{ $setting->id }})"
                                    class="mt-6 text-red-600 hover:text-red-800 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteSetting(id) {
    if (confirm('Are you sure you want to delete this setting?')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.sms.settings.destroy", ":id") }}'.replace(':id', id);
        form.submit();
    }
}
</script>
@endsection

