@extends('layouts.admin')

@section('page-title', 'Upload Payment Confirmation Sheet')

@push('styles')
<style>
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e5e7eb;
        z-index: 0;
    }
    
    .step-item {
        flex: 1;
        position: relative;
        z-index: 1;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #9ca3af;
        margin: 0 auto 0.5rem;
        transition: all 0.3s;
    }
    
    .step-item.active .step-circle {
        background: #015425;
        border-color: #015425;
        color: white;
    }
    
    .step-item.completed .step-circle {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    .step-label {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .step-item.active .step-label {
        color: #015425;
        font-weight: 600;
    }
    
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        padding: 3rem 2rem;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .upload-area:hover {
        border-color: #015425;
        background: #f0fdf4;
    }
    
    .upload-area.dragover {
        border-color: #015425;
        background: #f0fdf4;
        border-style: solid;
    }
    
    .file-info {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: #e0f2fe;
        border-radius: 0.5rem;
        border-left: 4px solid #015425;
    }
    
    .file-info.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Upload Payment Confirmation Sheet</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Bulk upload member payment information from Excel file</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.payment-confirmations.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step-item active" id="step-indicator-1">
            <div class="step-circle">1</div>
            <div class="step-label">Upload File</div>
        </div>
        <div class="step-item" id="step-indicator-2">
            <div class="step-circle">2</div>
            <div class="step-label">Map Columns</div>
        </div>
        <div class="step-item" id="step-indicator-3">
            <div class="step-circle">3</div>
            <div class="step-label">Process</div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div class="whitespace-pre-line">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('results'))
        @php $results = session('results'); @endphp
        <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 px-4 py-3 rounded-md shadow-sm">
            <h3 class="font-semibold mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Import Results
            </h3>
            <div class="grid grid-cols-3 gap-4 text-sm mb-3">
                <div>
                    <span class="font-medium">Total:</span> <span class="font-bold">{{ $results['total'] }}</span>
                </div>
                <div class="text-green-700">
                    <span class="font-medium">Success:</span> <span class="font-bold">{{ $results['success'] }}</span>
                </div>
                <div class="text-red-700">
                    <span class="font-medium">Failed:</span> <span class="font-bold">{{ $results['failed'] }}</span>
                </div>
            </div>
            @if(!empty($results['errors']))
                <div class="mt-3 max-h-60 overflow-y-auto">
                    <p class="font-medium mb-1">Errors:</p>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach(array_slice($results['errors'], 0, 20) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        @if(count($results['errors']) > 20)
                            <li class="text-gray-600">... and {{ count($results['errors']) - 20 }} more errors</li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Step 1: Upload Excel File -->
    <div id="step-1" class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Step 1: Upload Excel File</h3>
            <a href="{{ route('admin.payment-confirmations.download-sample') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Sample Excel
            </a>
        </div>
        
        <div class="space-y-4">
            <div class="upload-area" id="upload-area">
                <input type="file" 
                       name="excel_file" 
                       id="excel_file" 
                       accept=".xlsx,.xls,.csv"
                       class="hidden">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700 mb-2">Click to upload or drag and drop</p>
                    <p class="text-sm text-gray-500 mb-4">Excel files only (.xlsx, .xls, .csv)</p>
                    <button type="button" 
                            onclick="document.getElementById('excel_file').click()"
                            class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                        Select File
                    </button>
                </div>
            </div>
            
            <div class="file-info" id="file-info">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#015425] mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900" id="file-name"></p>
                            <p class="text-xs text-gray-500" id="file-size"></p>
                        </div>
                    </div>
                    <button type="button" 
                            id="remove-file"
                            class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <p class="text-sm text-blue-800">
                    <strong>Expected columns:</strong> S/N, Members Name, Member type, ID, Amount to be paid. 2026
                </p>
            </div>
            
            <button type="button" 
                    id="preview-btn"
                    class="w-full px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview Excel File
                </span>
            </button>
        </div>
    </div>

    <!-- Step 2: Select Sheet & Map Columns -->
    <div id="step-2" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 2: Select Sheet & Map Columns</h3>
        
        <!-- Sheet Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Select Sheet <span class="text-red-500">*</span>
            </label>
            <select id="sheet-select" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] py-2 px-3">
                <option value="">-- Select a sheet --</option>
            </select>
        </div>

        <!-- Column Mapping -->
        <div id="column-mapping-section" class="hidden">
            <h4 class="font-semibold text-gray-800 mb-3">Map Your Columns</h4>
            <p class="text-xs text-gray-500 mb-4">
                Select which Excel column corresponds to each field. Required fields are marked with <span class="text-red-500">*</span>.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member ID <span class="text-red-500">*</span>
                    </label>
                    <select name="column_mapping[member_id]" 
                            id="map-member-id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] py-2 px-3">
                        <option value="">-- Select column --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Amount to be Paid <span class="text-red-500">*</span>
                    </label>
                    <select name="column_mapping[amount]" 
                            id="map-amount"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] py-2 px-3">
                        <option value="">-- Select column --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member Name <span class="text-gray-400">(Optional)</span>
                    </label>
                    <select name="column_mapping[member_name]" 
                            id="map-member-name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] py-2 px-3">
                        <option value="">-- Select column (optional) --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member Type <span class="text-gray-400">(Optional)</span>
                    </label>
                    <select name="column_mapping[member_type]" 
                            id="map-member-type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425] py-2 px-3">
                        <option value="">-- Select column (optional) --</option>
                    </select>
                </div>
            </div>

            <!-- Preview Table -->
            <div id="preview-table-section" class="hidden">
                <h4 class="font-semibold text-gray-800 mb-3">Preview (First 5 rows)</h4>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead id="preview-headers" class="bg-gray-50">
                        </thead>
                        <tbody id="preview-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Process Upload -->
    <div id="step-3" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 3: Process Upload</h3>
        
        <form id="upload-form" method="POST" action="{{ route('admin.payment-confirmations.process-upload') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="excel_file" id="form-excel-file" style="display: none;" accept=".xlsx,.xls,.csv">
            <input type="hidden" id="form-sheet-index" name="sheet_index">
            <input type="hidden" id="form-column-mapping" name="column_mapping">
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 px-4 py-3 rounded-md mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">Important Note</p>
                        <p class="text-sm mt-1">This will create payment confirmation records for members. Members will need to complete the distribution form on the public page.</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Process Upload
                </span>
            </button>
        </form>
    </div>
</div>

<!-- Upload Splash Screen -->
<div id="uploadSplashScreen" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center shadow-2xl">
        <div class="mb-6">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#015425]"></div>
        </div>
        <div class="text-4xl font-bold text-[#015425] mb-2" id="uploadProgress">0%</div>
        <div class="text-lg text-gray-700 mb-4" id="uploadMessage">Processing your upload...</div>
        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
            <div class="bg-[#015425] h-3 rounded-full transition-all duration-300" id="uploadProgressBar" style="width: 0%"></div>
        </div>
        <div class="text-sm text-gray-500" id="uploadStatus">Preparing to upload...</div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const excelFileInput = document.getElementById('excel_file');
    const previewBtn = document.getElementById('preview-btn');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const sheetSelect = document.getElementById('sheet-select');
    const columnMappingSection = document.getElementById('column-mapping-section');
    const mapMemberId = document.getElementById('map-member-id');
    const mapAmount = document.getElementById('map-amount');
    const mapMemberName = document.getElementById('map-member-name');
    const mapMemberType = document.getElementById('map-member-type');
    const previewTableSection = document.getElementById('preview-table-section');
    const previewHeaders = document.getElementById('preview-headers');
    const previewBody = document.getElementById('preview-body');
    const uploadForm = document.getElementById('upload-form');
    const uploadArea = document.getElementById('upload-area');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');
    
    let currentSheets = [];
    let currentSheetData = null;
    let currentSheetIndex = 0;

    // File input change handler
    excelFileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            showFileInfo(file);
            previewBtn.disabled = false;
        } else {
            hideFileInfo();
            previewBtn.disabled = true;
        }
    });

    // Remove file handler
    removeFileBtn.addEventListener('click', function() {
        excelFileInput.value = '';
        hideFileInfo();
        previewBtn.disabled = true;
        step2.classList.add('hidden');
        step3.classList.add('hidden');
        updateStepIndicator(1);
    });

    function showFileInfo(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.add('show');
        uploadArea.style.borderColor = '#10b981';
        uploadArea.style.background = '#f0fdf4';
    }

    function hideFileInfo() {
        fileInfo.classList.remove('show');
        uploadArea.style.borderColor = '#d1d5db';
        uploadArea.style.background = '#f9fafb';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            excelFileInput.files = files;
            excelFileInput.dispatchEvent(new Event('change'));
        }
    });

    // Preview button click handler
    previewBtn.addEventListener('click', async function() {
        const file = excelFileInput.files[0];
        if (!file) {
            alert('Please select an Excel file first.');
            return;
        }

        previewBtn.disabled = true;
        previewBtn.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...</span>';

        const formData = new FormData();
        formData.append('excel_file', file);

        try {
            const response = await fetch('{{ route("admin.payment-confirmations.preview-excel") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                currentSheets = data.sheets;
                
                // Populate sheet select
                sheetSelect.innerHTML = '<option value="">-- Select a sheet --</option>';
                data.sheets.forEach((sheet, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = `${sheet.name} (${sheet.row_count} rows)`;
                    sheetSelect.appendChild(option);
                });

                step2.classList.remove('hidden');
                updateStepIndicator(2);
                step2.scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Error: ' + (data.error || 'Failed to preview Excel file'));
            }
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            previewBtn.disabled = false;
            previewBtn.innerHTML = '<span class="flex items-center justify-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>Preview Excel File</span>';
        }
    });

    sheetSelect.addEventListener('change', function() {
        const sheetIndex = parseInt(this.value);
        if (isNaN(sheetIndex)) {
            columnMappingSection.classList.add('hidden');
            step3.classList.add('hidden');
            return;
        }

        currentSheetIndex = sheetIndex;
        currentSheetData = currentSheets[sheetIndex];

        // Populate column selects
        const headers = currentSheetData.headers;
        
        [mapMemberId, mapAmount, mapMemberName, mapMemberType].forEach(select => {
            if (select) {
                const isOptional = select === mapMemberName || select === mapMemberType;
                select.innerHTML = isOptional ? '<option value="">-- Select column (optional) --</option>' : '<option value="">-- Select column --</option>';
                headers.forEach((header, index) => {
                    const option = document.createElement('option');
                    option.value = header;
                    option.textContent = header;
                    select.appendChild(option);
                });
            }
        });

        // Show preview
        showPreview(currentSheetData);

        columnMappingSection.classList.remove('hidden');
        step3.classList.remove('hidden');
        updateStepIndicator(3);
        step3.scrollIntoView({ behavior: 'smooth' });
    });

    function showPreview(sheetData) {
        const headers = sheetData.headers;
        const sampleData = sheetData.sample_data;

        // Build header row
        previewHeaders.innerHTML = '<tr>';
        headers.forEach(header => {
            previewHeaders.innerHTML += `<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">${header}</th>`;
        });
        previewHeaders.innerHTML += '</tr>';

        // Build body rows
        previewBody.innerHTML = '';
        sampleData.forEach(row => {
            previewBody.innerHTML += '<tr class="hover:bg-gray-50">';
            headers.forEach((header, index) => {
                previewBody.innerHTML += `<td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">${row[index] || ''}</td>`;
            });
            previewBody.innerHTML += '</tr>';
        });

        previewTableSection.classList.remove('hidden');
    }

    function updateStepIndicator(activeStep) {
        for (let i = 1; i <= 3; i++) {
            const indicator = document.getElementById(`step-indicator-${i}`);
            indicator.classList.remove('active', 'completed');
            if (i < activeStep) {
                indicator.classList.add('completed');
            } else if (i === activeStep) {
                indicator.classList.add('active');
            }
        }
    }

    // Function to refresh CSRF token
    async function refreshCsrfToken() {
        try {
            const response = await fetch('{{ route("admin.payment-confirmations.refresh-csrf") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.token) {
                const newToken = data.token;
                
                // Update form token
                const formToken = uploadForm.querySelector('input[name="_token"]');
                if (formToken) {
                    formToken.value = newToken;
                }
                
                // Update meta tag if exists
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', newToken);
                }
                
                return newToken;
            }
        } catch (error) {
            console.error('Error refreshing CSRF token:', error);
        }
        return null;
    }

    uploadForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent default submission
        
        const file = excelFileInput.files[0];
        if (!file) {
            alert('Please select an Excel file.');
            return false;
        }

        const sheetIndex = sheetSelect.value;
        if (!sheetIndex) {
            alert('Please select a sheet.');
            return false;
        }

        const memberIdColumn = mapMemberId.value;
        const amountColumn = mapAmount.value;
        const memberNameColumn = mapMemberName?.value || '';
        const memberTypeColumn = mapMemberType?.value || '';

        if (!memberIdColumn || !amountColumn) {
            alert('Please map all required columns (Member ID and Amount).');
            return false;
        }

        // Get splash screen elements
        const uploadSplashScreen = document.getElementById('uploadSplashScreen');
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadProgressBar = document.getElementById('uploadProgressBar');
        const uploadMessage = document.getElementById('uploadMessage');
        const uploadStatus = document.getElementById('uploadStatus');
        
        // Show splash screen first
        if (uploadSplashScreen) {
            uploadSplashScreen.style.display = 'flex';
            if (uploadStatus) {
                uploadStatus.textContent = 'Refreshing session...';
            }
        }

        // Refresh CSRF token before submission
        const newToken = await refreshCsrfToken();
        
        if (!newToken) {
            if (uploadSplashScreen) uploadSplashScreen.style.display = 'none';
            alert('Failed to refresh session. Please refresh the page and try again.');
            return false;
        }

        // Set hidden form values
        const formExcelFile = document.getElementById('form-excel-file');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        formExcelFile.files = dataTransfer.files;
        
        const columnMapping = {
            member_id: memberIdColumn,
            amount: amountColumn,
            member_name: memberNameColumn,
            member_type: memberTypeColumn
        };
        
        document.getElementById('form-sheet-index').value = sheetIndex;
        document.getElementById('form-column-mapping').value = JSON.stringify(columnMapping);
        
        // Start progress animation
        if (uploadSplashScreen) {
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 2;
                if (progress <= 90) {
                    if (uploadProgress) uploadProgress.textContent = progress + '%';
                    if (uploadProgressBar) uploadProgressBar.style.width = progress + '%';
                    
                    // Update status messages
                    if (uploadStatus) {
                        if (progress < 20) {
                            uploadStatus.textContent = 'Validating file...';
                        } else if (progress < 40) {
                            uploadStatus.textContent = 'Reading Excel data...';
                        } else if (progress < 60) {
                            uploadStatus.textContent = 'Processing rows...';
                        } else if (progress < 80) {
                            uploadStatus.textContent = 'Saving to database...';
                        } else {
                            uploadStatus.textContent = 'Finalizing...';
                        }
                    }
                }
            }, 100);
            
            // Store interval to clear it later
            window.uploadProgressInterval = progressInterval;
        }
        
        // Show loading overlay
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...</span>';
        }
        
        // Disable form inputs to prevent double submission
        this.querySelectorAll('input, select, button').forEach(el => {
            if (el !== submitBtn && el !== formExcelFile) {
                el.disabled = true;
            }
        });

        // Submit the form programmatically after a short delay
        setTimeout(() => {
            this.submit();
        }, 300);
        
        return false;
    });
    
    // Complete progress when page is about to unload
    window.addEventListener('beforeunload', function() {
        if (window.uploadProgressInterval) {
            clearInterval(window.uploadProgressInterval);
        }
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadProgressBar = document.getElementById('uploadProgressBar');
        const uploadStatus = document.getElementById('uploadStatus');
        if (uploadProgress) {
            uploadProgress.textContent = '100%';
            uploadProgressBar.style.width = '100%';
            uploadStatus.textContent = 'Upload complete! Redirecting...';
        }
    });
});
</script>
@endpush
@endsection
