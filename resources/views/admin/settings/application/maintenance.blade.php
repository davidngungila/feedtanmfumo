@extends('layouts.admin')

@section('page-title', 'Maintenance Mode')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Maintenance Mode</h1>
        <p class="text-white text-opacity-90">Control system maintenance mode</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            <div class="p-4 border-2 rounded-lg {{ $isMaintenance ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Maintenance Mode Status</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($isMaintenance)
                                System is currently in maintenance mode. Users cannot access the application.
                            @else
                                System is running normally. All users can access the application.
                            @endif
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $isMaintenance ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $isMaintenance ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.system-settings.toggle-maintenance-mode') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Secret (Optional)</label>
                        <input type="text" name="secret" placeholder="Leave empty for default" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <p class="mt-1 text-sm text-gray-500">Secret key to bypass maintenance mode</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 {{ $isMaintenance ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-md">
                            {{ $isMaintenance ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

