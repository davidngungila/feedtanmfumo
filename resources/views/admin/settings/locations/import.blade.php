@extends('layouts.admin')

@section('page-title', 'Import Locations')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Import Locations from CSV</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Upload multiple CSV files to import locations at once</p>
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

    <!-- CSV Import Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Upload CSV Files</h3>
                <p class="text-sm text-gray-600 mt-1">Select one or more CSV files to import locations</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.locations.export') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Sample
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.locations.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Multiple File Upload -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <div class="mb-4">
                        <label for="csv_files" class="cursor-pointer">
                            <span class="inline-flex items-center px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Choose CSV Files
                            </span>
                            <input type="file" id="csv_files" name="csv_files[]" accept=".csv,.txt" multiple class="hidden">
                        </label>
                    </div>
                    <div class="mb-2">
                        <p class="text-sm text-gray-600">
                            <span id="file-count">No files selected</span>
                        </p>
                        <div id="file-list" class="mt-2 space-y-1 max-h-32 overflow-y-auto"></div>
                    </div>
                    <p class="text-xs text-gray-500">CSV files only, max 10MB per file, multiple files allowed</p>
                </div>
            </div>

            <!-- Format Requirements -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-blue-900 mb-3">CSV Format Requirements:</h4>
                        <ul class="text-sm text-blue-800 space-y-2">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span><strong>Required columns:</strong> <code class="bg-blue-100 px-2 py-1 rounded text-xs">region, regioncode, district, districtcode, ward, wardcode, street, places</code></span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>First row must contain column headers</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Region, District, and Ward are required fields</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Duplicate locations will be skipped automatically</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Codes will be automatically converted to uppercase</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Multiple files can be selected and processed in sequence</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Import Progress -->
            <div id="import-progress" class="hidden">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Import Progress</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Processing files...</span>
                            <span id="progress-text">0 / 0</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-[#015425] h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div id="progress-details" class="text-xs text-gray-600 mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <button type="button" onclick="resetImportForm()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Clear
                </button>
                <button type="submit" id="import-btn" disabled class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Import Locations
                </button>
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

// CSV Import Form JavaScript for Import Page
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('csv_files');
    const fileCount = document.getElementById('file-count');
    const fileList = document.getElementById('file-list');
    const importBtn = document.getElementById('import-btn');
    const importProgress = document.getElementById('import-progress');
    const progressText = document.getElementById('progress-text');
    const progressBar = document.getElementById('progress-bar');
    const progressDetails = document.getElementById('progress-details');
    
    // Handle multiple file selection
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            // Validate files
            let validFiles = [];
            let hasErrors = false;
            
            files.forEach(file => {
                // Validate file type
                const allowedTypes = ['text/csv', 'text/plain', 'application/csv'];
                if (!allowedTypes.includes(file.type) && !file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
                    alert(`File "${file.name}" is not a valid CSV file.`);
                    hasErrors = true;
                    return;
                }
                
                // Validate file size (10MB max)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert(`File "${file.name}" exceeds 10MB size limit.`);
                    hasErrors = true;
                    return;
                }
                
                validFiles.push(file);
            });
            
            if (hasErrors) {
                resetImportForm();
                return;
            }
            
            // Update UI
            fileCount.textContent = `${validFiles.length} file(s) selected`;
            fileList.innerHTML = '';
            
            validFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded';
                fileItem.innerHTML = `
                    <span class="font-medium">${index + 1}.</span> ${file.name} (${formatFileSize(file.size)})
                `;
                fileList.appendChild(fileItem);
            });
            
            importBtn.disabled = false;
        } else {
            resetImportForm();
        }
    });
    
    // Handle form submission
    const importForm = document.querySelector('form[action*="import"]');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            const files = fileInput.files;
            
            if (files.length === 0) {
                e.preventDefault();
                alert('Please select at least one CSV file to import.');
                return;
            }
            
            // Show progress section
            importProgress.classList.remove('hidden');
            progressText.textContent = `0 / ${files.length}`;
            progressBar.style.width = '0%';
            progressDetails.textContent = 'Starting import...';
            
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

// Format file size helper
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Reset import form function
function resetImportForm() {
    const fileInput = document.getElementById('csv_files');
    const fileCount = document.getElementById('file-count');
    const fileList = document.getElementById('file-list');
    const importBtn = document.getElementById('import-btn');
    const importProgress = document.getElementById('import-progress');
    
    fileInput.value = '';
    fileCount.textContent = 'No files selected';
    fileList.innerHTML = '';
    importBtn.disabled = true;
    importProgress.classList.add('hidden');
    importBtn.innerHTML = `
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
        Import Locations
    `;
}
</script>
@endsection
