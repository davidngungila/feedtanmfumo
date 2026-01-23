@extends('layouts.admin')

@section('page-title', 'System Updates')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">System Updates</h1>
        <p class="text-white text-opacity-90">Check for system updates</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Version -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Version</h2>
            @if($currentVersion)
            <div class="space-y-3">
                <div>
                    <span class="text-2xl font-bold text-[#015425]">{{ $currentVersion->version }}</span>
                    @if($currentVersion->codename)
                    <span class="text-sm text-gray-500 ml-2">({{ $currentVersion->codename }})</span>
                    @endif
                </div>
                <div class="text-sm text-gray-600">Released: {{ $currentVersion->release_date->format('M d, Y') }}</div>
            </div>
            @else
            <p class="text-sm text-gray-500">Version information not available</p>
            @endif
        </div>

        <!-- Available Updates -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Available Updates</h2>
            <div class="space-y-3">
                @forelse($availableVersions as $version)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $version->version }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $version->release_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ ucfirst($version->type) }} update</p>
                        </div>
                        <button class="px-3 py-1 bg-[#015425] text-white text-sm rounded-md hover:bg-[#027a3a]">
                            Update
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500">No updates available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

