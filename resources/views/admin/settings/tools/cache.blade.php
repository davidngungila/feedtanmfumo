@extends('layouts.admin')

@section('page-title', 'Cache Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Cache Management</h1>
        <p class="text-white text-opacity-90">Clear and manage system cache</p>
    </div>

    <!-- Cache Options -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.clear-cache') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Clear Cache</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#015425] transition">
                        <input type="radio" name="type" value="all" class="text-[#015425] focus:ring-[#015425]" checked>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Clear All Cache</div>
                            <div class="text-sm text-gray-500">Clear application, config, route, and view cache</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#015425] transition">
                        <input type="radio" name="type" value="cache" class="text-[#015425] focus:ring-[#015425]">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Application Cache</div>
                            <div class="text-sm text-gray-500">Clear application cache only</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#015425] transition">
                        <input type="radio" name="type" value="config" class="text-[#015425] focus:ring-[#015425]">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Config Cache</div>
                            <div class="text-sm text-gray-500">Clear configuration cache</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#015425] transition">
                        <input type="radio" name="type" value="route" class="text-[#015425] focus:ring-[#015425]">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Route Cache</div>
                            <div class="text-sm text-gray-500">Clear route cache</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#015425] transition">
                        <input type="radio" name="type" value="view" class="text-[#015425] focus:ring-[#015425]">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">View Cache</div>
                            <div class="text-sm text-gray-500">Clear compiled view cache</div>
                        </div>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">
                        Clear Cache
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


