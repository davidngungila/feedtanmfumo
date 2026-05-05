@extends('layouts.admin')

@section('page-title', 'Edit Location')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Location</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Update location details for {{ $location->region }}, {{ $location->district }}</p>
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

    <!-- Edit Form Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('region', $location->region) }}" 
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
                               value="{{ old('regioncode', $location->regioncode) }}" 
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
                               value="{{ old('district', $location->district) }}" 
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
                               value="{{ old('districtcode', $location->districtcode) }}" 
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
                               value="{{ old('ward', $location->ward) }}" 
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
                               value="{{ old('wardcode', $location->wardcode) }}" 
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
                               value="{{ old('street', $location->street) }}" 
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
                                  placeholder="e.g., Kariakoo Market, Posta, Swahili Street">{{ old('places', $location->places) }}</textarea>
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
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                        Update Location
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

// CSV Import Form JavaScript for Edit Page
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('csv_file_edit');
    const fileName = document.getElementById('file-name-edit');
    const importBtn = document.getElementById('import-btn-edit');
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file type
            const allowedTypes = ['text/csv', 'text/plain', 'application/csv'];
            if (!allowedTypes.includes(file.type) && !file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
                alert('Please select a CSV file.');
                resetImportFormEdit();
                return;
            }
            
            // Validate file size (10MB max)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 10MB.');
                resetImportFormEdit();
                return;
            }
            
            fileName.textContent = file.name;
            importBtn.disabled = false;
        } else {
            resetImportFormEdit();
        }
    });
    
    // Handle form submission
    const importForm = document.querySelector('form[action*="import"]');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            
            if (!file) {
                e.preventDefault();
                alert('Please select a CSV file to import.');
                return;
            }
            
            // Show loading state
            importBtn.disabled = true;
            importBtn.innerHTML = `
                <svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Importing...
            `;
        });
    }
});

// Reset import form function for Edit Page
function resetImportFormEdit() {
    const fileInput = document.getElementById('csv_file_edit');
    const fileName = document.getElementById('file-name-edit');
    const importBtn = document.getElementById('import-btn-edit');
    
    fileInput.value = '';
    fileName.textContent = 'No file selected';
    importBtn.disabled = true;
    importBtn.innerHTML = `
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
        Import Locations
    `;
}
</script>
@endsection
