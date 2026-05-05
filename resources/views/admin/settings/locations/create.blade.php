@extends('layouts.admin')

@section('page-title', 'Add Location')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Add New Location</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Add a new Tanzania location with region, district, and ward details</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-green-300 rounded-md hover:bg-opacity-30 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Locations
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.locations.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                            Region <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="region" 
                               name="region" 
                               value="{{ old('region') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('region') border-red-500 @enderror"
                               placeholder="Enter region name">
                        @error('region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Region Code -->
                    <div>
                        <label for="regioncode" class="block text-sm font-medium text-gray-700 mb-2">
                            Region Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="regioncode" 
                               name="regioncode" 
                               value="{{ old('regioncode') }}" 
                               maxlength="5" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('regioncode') border-red-500 @enderror"
                               placeholder="e.g., AR">
                        @error('regioncode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                            District <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="district" 
                               name="district" 
                               value="{{ old('district') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('district') border-red-500 @enderror"
                               placeholder="Enter district name">
                        @error('district')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- District Code -->
                    <div>
                        <label for="districtcode" class="block text-sm font-medium text-gray-700 mb-2">
                            District Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="districtcode" 
                               name="districtcode" 
                               value="{{ old('districtcode') }}" 
                               maxlength="5" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('districtcode') border-red-500 @enderror"
                               placeholder="e.g., ARC">
                        @error('districtcode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Ward -->
                    <div>
                        <label for="ward" class="block text-sm font-medium text-gray-700 mb-2">
                            Ward <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="ward" 
                               name="ward" 
                               value="{{ old('ward') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('ward') border-red-500 @enderror"
                               placeholder="Enter ward name">
                        @error('ward')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ward Code -->
                    <div>
                        <label for="wardcode" class="block text-sm font-medium text-gray-700 mb-2">
                            Ward Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="wardcode" 
                               name="wardcode" 
                               value="{{ old('wardcode') }}" 
                               maxlength="5" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('wardcode') border-red-500 @enderror"
                               placeholder="e.g., SEK">
                        @error('wardcode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Street -->
                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700 mb-2">
                            Street <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="street" 
                               name="street" 
                               value="{{ old('street') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('street') border-red-500 @enderror"
                               placeholder="Enter street name">
                        @error('street')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Places -->
                    <div>
                        <label for="places" class="block text-sm font-medium text-gray-700 mb-2">
                            Places <span class="text-red-500">*</span>
                        </label>
                        <textarea id="places" 
                                  name="places" 
                                  rows="4" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] @error('places') border-red-500 @enderror"
                                  placeholder="e.g., Kariakoo Market, Posta, Swahili Street">{{ old('places') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Separate multiple places with commas</p>
                        @error('places')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-between border-t pt-6">
                <div>
                    <a href="{{ route('admin.locations.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                        Cancel
                    </a>
                </div>
                <div class="flex space-x-3">
                    <button type="reset" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                        Clear Form
                    </button>
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                        Add Location
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div id="flash-success" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="flash-error" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
@endif

<script>
// Auto-hide flash messages after 5 seconds
setTimeout(function() {
    const successMsg = document.getElementById('flash-success');
    const errorMsg = document.getElementById('flash-error');
    
    if (successMsg) {
        successMsg.remove();
    }
    if (errorMsg) {
        errorMsg.remove();
    }
}, 5000);
</script>
@endsection
