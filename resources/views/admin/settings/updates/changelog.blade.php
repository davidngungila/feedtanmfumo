@extends('layouts.admin')

@section('page-title', 'Changelog')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Changelog</h1>
        <p class="text-white text-opacity-90">View version history and changes</p>
    </div>

    <div class="space-y-4">
        @forelse($versions as $version)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $version->version }}</h2>
                    @if($version->codename)
                    <p class="text-sm text-gray-600">Codename: {{ $version->codename }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">{{ $version->release_date->format('M d, Y') }}</div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mt-1 inline-block">
                        {{ ucfirst($version->type) }}
                    </span>
                </div>
            </div>
            @if($version->changelog)
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $version->changelog }}</div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-500">No version history available</p>
        </div>
        @endforelse
    </div>
</div>
@endsection


