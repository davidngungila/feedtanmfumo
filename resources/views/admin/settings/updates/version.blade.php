@extends('layouts.admin')

@section('page-title', 'Version Info')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Version Information</h1>
        <p class="text-white text-opacity-90">Current system version details</p>
    </div>

    @if($version)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-[#015425]">{{ $version->version }}</h2>
                @if($version->codename)
                <p class="text-gray-600 mt-1">Codename: {{ $version->codename }}</p>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Release Date</label>
                    <div class="text-sm text-gray-900">{{ $version->release_date->format('F d, Y') }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <div class="text-sm text-gray-900">{{ ucfirst($version->type) }}</div>
                </div>
            </div>
            @if($version->changelog)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Changelog</label>
                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $version->changelog }}</div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-sm text-gray-500">Version information not available</p>
    </div>
    @endif
</div>
@endsection

