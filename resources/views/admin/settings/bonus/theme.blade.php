@extends('layouts.admin')

@section('page-title', 'Theme / Appearance')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Theme / Appearance</h1>
        <p class="text-white text-opacity-90">Customize system theme and appearance</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.theme-appearance.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                    <input type="color" name="theme_primary_color" value="{{ $settings['theme_primary_color']->value ?? '#015425' }}" 
                           class="w-full h-12 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                    <input type="color" name="theme_secondary_color" value="{{ $settings['theme_secondary_color']->value ?? '#027a3a' }}" 
                           class="w-full h-12 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <input type="file" name="theme_logo" accept="image/*" 
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

